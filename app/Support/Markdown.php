<?php

namespace App\Support;

/**
 * Conversor minimo de Markdown para HTML — mesmo parser usado em painel/index.php,
 * portado para reaproveitar a leitura de atas/agendas dentro do SGM Gerencial.
 * Cobre: titulos, tabelas, listas (com checkbox), blocos de codigo (incl. mermaid),
 * citacao, negrito/italico/codigo/link, tachado e linha horizontal.
 */
class Markdown
{
    public static function inline(string $text): string
    {
        $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        $text = preg_replace('/`([^`]+)`/', '<code>$1</code>', $text);
        $text = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $text);
        $text = preg_replace('/~~(.+?)~~/', '<del>$1</del>', $text);
        $text = preg_replace('/(?<!\*)\*(?!\*)([^*]+)\*(?!\*)/', '<em>$1</em>', $text);
        $text = preg_replace('/\[([^\]]+)\]\(([^)]+)\)/', '<a href="$2" target="_blank" rel="noopener">$1</a>', $text);

        return $text;
    }

    public static function toHtml(string $src): string
    {
        $lines = preg_split('/\r\n|\r|\n/', $src);
        $out = [];
        $n = count($lines);
        $i = 0;
        $para = [];

        $flushPara = function () use (&$para, &$out) {
            if (! empty($para)) {
                $out[] = '<p>'.self::inline(trim(implode(' ', $para))).'</p>';
                $para = [];
            }
        };

        while ($i < $n) {
            $line = $lines[$i];
            $trim = trim($line);

            if (preg_match('/^```(\w*)/', $trim, $m)) {
                $flushPara();
                $lang = $m[1];
                $code = [];
                $i++;
                while ($i < $n && trim($lines[$i]) !== '```') {
                    $code[] = $lines[$i];
                    $i++;
                }
                $i++;
                $codeText = implode("\n", $code);
                if ($lang === 'mermaid') {
                    $out[] = '<pre class="mermaid">'.htmlspecialchars($codeText, ENT_QUOTES, 'UTF-8').'</pre>';
                } else {
                    $out[] = '<pre class="code"><code>'.htmlspecialchars($codeText, ENT_QUOTES, 'UTF-8').'</code></pre>';
                }
                continue;
            }

            if ($trim === '') {
                $flushPara();
                $i++;
                continue;
            }

            if (preg_match('/^-{3,}$/', $trim)) {
                $flushPara();
                $out[] = '<hr>';
                $i++;
                continue;
            }

            if (preg_match('/^(#{1,6})\s+(.*)$/', $trim, $m)) {
                $flushPara();
                $level = strlen($m[1]);
                $out[] = "<h{$level}>".self::inline($m[2])."</h{$level}>";
                $i++;
                continue;
            }

            if (strpos($trim, '>') === 0) {
                $flushPara();
                $quote = [];
                while ($i < $n && strpos(trim($lines[$i]), '>') === 0) {
                    $quote[] = preg_replace('/^>\s?/', '', trim($lines[$i]));
                    $i++;
                }
                $out[] = '<blockquote>'.self::inline(implode(' ', $quote)).'</blockquote>';
                continue;
            }

            if (strpos($trim, '|') !== false && isset($lines[$i + 1]) && preg_match('/^\|?[\s:|-]+\|[\s:|-]+\|?$/', trim($lines[$i + 1]))) {
                $flushPara();
                $header = array_map('trim', explode('|', trim($trim, '|')));
                $i += 2;
                $rows = [];
                while ($i < $n && strpos(trim($lines[$i]), '|') !== false && trim($lines[$i]) !== '') {
                    $rows[] = array_map('trim', explode('|', trim(trim($lines[$i]), '|')));
                    $i++;
                }
                $html = '<div class="table-wrap"><table><thead><tr>';
                foreach ($header as $h) {
                    $html .= '<th>'.self::inline($h).'</th>';
                }
                $html .= '</tr></thead><tbody>';
                foreach ($rows as $row) {
                    $html .= '<tr>';
                    foreach ($row as $cell) {
                        $html .= '<td>'.self::inline($cell).'</td>';
                    }
                    $html .= '</tr>';
                }
                $html .= '</tbody></table></div>';
                $out[] = $html;
                continue;
            }

            if (preg_match('/^(-|\*|\d+\.)\s+(.*)$/', $trim)) {
                $flushPara();
                $items = [];
                $ordered = preg_match('/^\d+\.\s/', $trim);
                while ($i < $n && preg_match('/^(-|\*|\d+\.)\s+(.*)$/', trim($lines[$i]), $m)) {
                    $content = $m[2];
                    if (preg_match('/^\[( |x|X)\]\s*(.*)$/', $content, $cb)) {
                        $checked = strtolower($cb[1]) === 'x';
                        $cls = $checked ? 'done' : 'pending';
                        $items[] = '<li class="task '.$cls.'"><span class="box"></span>'.self::inline($cb[2]).'</li>';
                    } else {
                        $items[] = '<li>'.self::inline($content).'</li>';
                    }
                    $i++;
                }
                $tag = $ordered ? 'ol' : 'ul';
                $out[] = "<{$tag}>".implode('', $items)."</{$tag}>";
                continue;
            }

            $para[] = $line;
            $i++;
        }
        $flushPara();

        return implode("\n", $out);
    }
}
