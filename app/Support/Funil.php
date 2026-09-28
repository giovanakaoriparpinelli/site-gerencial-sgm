<?php

namespace App\Support;

class Funil
{
    /**
     * Etapas do funil comercial da SGM Empresarial, na ordem em que o cliente avança.
     * Cada etapa traz o rótulo, o objetivo e a próxima ação típica (usados no dashboard
     * do funil) e a lista de campos do checklist daquela etapa (usados no card de
     * checklist de cada cliente).
     */
    public static function etapas(): array
    {
        return [
            'prospeccao' => [
                'label' => 'Prospecção / Levantamento',
                'objetivo' => 'Encontrar oportunidades reais',
                'proxima_acao' => 'Fazer mini-diagnóstico',
                'campos' => [
                    'nome_empresa' => 'Nome da empresa',
                    'segmento' => 'Segmento',
                    'cidade' => 'Cidade',
                    'responsavel' => 'Responsável',
                    'whatsapp' => 'WhatsApp',
                    'instagram' => 'Instagram',
                    'google_maps' => 'Google / Google Maps',
                    'site' => 'Site',
                    'possui_site' => 'Possui site?',
                    'presenca_google' => 'Presença no Google',
                    'presenca_instagram' => 'Presença no Instagram',
                    'como_encontrei' => 'Como encontrei o cliente',
                    'principais_oportunidades' => 'Principais oportunidades',
                    'concorrentes_analisados' => 'Concorrentes analisados',
                    'minha_hipotese' => 'Minha hipótese',
                    'observacoes' => 'Observações',
                ],
            ],
            'primeiro_contato' => [
                'label' => 'Primeiro Contato',
                'objetivo' => 'Iniciar conversa',
                'proxima_acao' => 'Aguardar / responder',
                'campos' => [
                    'data_primeiro_contato' => 'Data do primeiro contato',
                    'canal' => 'Canal',
                    'mensagem_utilizada' => 'Mensagem utilizada',
                    'respondeu' => 'Respondeu?',
                    'interesse_inicial' => 'Interesse inicial',
                    'principal_reacao' => 'Principal reação',
                    'proximo_passo' => 'Próximo passo',
                ],
            ],
            'qualificacao' => [
                'label' => 'Qualificação',
                'objetivo' => 'Descobrir necessidade e momento',
                'proxima_acao' => 'Fazer perguntas consultivas',
                'campos' => [
                    'origem_clientes' => 'De onde vêm os clientes?',
                    'recebe_pela_internet' => 'Recebe clientes pela internet?',
                    'como_encontram' => 'Como as pessoas encontram a empresa?',
                    'instagram_gera_contatos' => 'Instagram gera contatos?',
                    'google_gera_contatos' => 'Google gera contatos?',
                    'agenda_cheia' => 'Agenda / demanda está cheia?',
                    'periodo_pouca_demanda' => 'Existe período com pouca demanda?',
                    'servico_quer_vender' => 'Qual serviço gostaria de vender mais?',
                    'principal_desafio' => 'Principal desafio comercial',
                    'ja_investe_anuncios' => 'Já investe em anúncios?',
                    'pretende_anunciar' => 'Pretende anunciar?',
                    'orcamento_momento' => 'Existe orçamento / momento para investir?',
                    'diagnostico_inicial' => 'Diagnóstico inicial',
                ],
            ],
            'diagnostico' => [
                'label' => 'Diagnóstico / Reunião',
                'objetivo' => 'Entender o negócio profundamente',
                'proxima_acao' => 'Definir solução',
                'campos' => [
                    'objetivo_principal' => 'Objetivo principal',
                    'publico_alvo' => 'Público-alvo',
                    'principal_produto' => 'Principal produto / serviço',
                    'diferenciais' => 'Diferenciais',
                    'problema_identificado' => 'Problema identificado',
                    'quer_melhorar' => 'O que quer melhorar',
                    'ja_utiliza' => 'O que já utiliza',
                    'funcionando' => 'O que está funcionando',
                    'nao_funcionando' => 'O que não está funcionando',
                    'espera_alcancar' => 'O que espera alcançar',
                    'solucao_sgm' => 'Solução que a SGM pode oferecer',
                    'nao_precisa_oferecer' => 'O que NÃO precisa ser oferecido',
                    'data_reuniao' => 'Data da reunião',
                    'principais_pontos' => 'Principais pontos',
                    'proximo_passo' => 'Próximo passo',
                ],
            ],
            'proposta' => [
                'label' => 'Proposta',
                'objetivo' => 'Apresentar solução adequada',
                'proxima_acao' => 'Follow-up',
                'campos' => [
                    'solucao_apresentada' => 'Solução apresentada',
                    'site' => 'Site',
                    'conteudo_redes' => 'Conteúdo / Redes sociais',
                    'trafego_pago' => 'Tráfego pago',
                    'manutencao' => 'Manutenção',
                    'outros_servicos' => 'Outros serviços',
                    'valor' => 'Valor',
                    'forma_pagamento' => 'Forma de pagamento',
                    'prazo' => 'Prazo',
                    'incluido' => 'Incluído',
                    'nao_incluido' => 'Não incluído',
                    'data_envio' => 'Data de envio',
                ],
            ],
            'negociacao' => [
                'label' => 'Negociação / Fechamento',
                'objetivo' => 'Resolver dúvidas e formalizar',
                'proxima_acao' => 'Fechar ou agendar retorno',
                'campos' => [
                    'objecao_principal' => 'Objeção principal',
                    'o_que_cliente_falou' => 'O que o cliente falou',
                    'como_respondi' => 'Como respondi',
                    'proximo_passo_combinado' => 'Próximo passo combinado',
                    'resultado' => 'Resultado',
                    'motivo_perda' => 'Motivo da perda',
                    'data_novo_contato' => 'Data para novo contato',
                ],
            ],
            'onboarding' => [
                'label' => 'Onboarding / Entrega',
                'objetivo' => 'Executar e entregar',
                'proxima_acao' => 'Coletar materiais / aprovar',
                'campos' => [
                    'contrato_enviado' => 'Contrato enviado',
                    'contrato_aprovado' => 'Contrato aprovado',
                    'pagamento_recebido' => 'Pagamento recebido',
                    'briefing_enviado' => 'Briefing enviado',
                    'briefing_preenchido' => 'Briefing preenchido',
                    'materiais_recebidos' => 'Materiais recebidos',
                    'estrutura_definida' => 'Estrutura definida',
                    'design' => 'Design',
                    'desenvolvimento' => 'Desenvolvimento',
                    'revisao' => 'Revisão',
                    'ajustes' => 'Ajustes',
                    'aprovacao' => 'Aprovação',
                    'site_publicado' => 'Site publicado',
                    'data_entrega' => 'Data de entrega',
                    'dominio' => 'Domínio',
                    'hospedagem' => 'Hospedagem',
                    'acessos_entregues' => 'Acessos entregues',
                    'orientacoes_dadas' => 'Orientações dadas',
                ],
            ],
            'posvenda' => [
                'label' => 'Pós-venda / Suporte / Expansão',
                'objetivo' => 'Acompanhar e identificar novas oportunidades',
                'proxima_acao' => 'Contato de acompanhamento',
                'campos' => [
                    'acompanhamento_7' => 'Acompanhamento 7 dias',
                    'acompanhamento_30' => 'Acompanhamento 30 dias',
                    'acompanhamento_60' => 'Acompanhamento 60 dias',
                    'acompanhamento_90' => 'Acompanhamento 90 dias',
                    'esta_satisfeito' => 'Está satisfeito?',
                    'recebendo_contatos' => 'Está recebendo contatos?',
                    'alguma_dificuldade' => 'Alguma dificuldade?',
                    'informacoes_atualizar' => 'Informações para atualizar?',
                    'gostaria_melhorar' => 'Algo que gostaria de melhorar?',
                    'manutencao' => 'Manutenção',
                    'atualizacoes' => 'Atualizações',
                    'google_seo' => 'Google / SEO',
                    'conteudo' => 'Conteúdo',
                    'redes_sociais' => 'Redes sociais',
                    'google_ads' => 'Google Ads',
                    'meta_ads' => 'Meta Ads',
                    'novo_projeto' => 'Novo projeto',
                    'indicacao' => 'Indicação',
                    'nova_oportunidade' => 'Nova oportunidade',
                    'proximo_contato' => 'Próximo contato',
                ],
            ],
            'recusa' => [
                'label' => 'Recusa',
                'objetivo' => 'Cliente recusou o contato/serviço',
                'proxima_acao' => 'Contatar em data marcada',
                'campos' => [
                    'motivo_recusa' => 'Motivo da recusa',
                    'pediu_recontato' => 'Pediu para recontato futuro?',
                    'data_novo_contato' => 'Data sugerida para novo contato',
                    'observacoes' => 'Observações',
                ],
            ],
        ];
    }

    public static function labels(): array
    {
        return array_map(fn ($e) => $e['label'], self::etapas());
    }

    public static function campos(string $etapa): array
    {
        return self::etapas()[$etapa]['campos'] ?? [];
    }

    public static function chaves(): array
    {
        return array_keys(self::etapas());
    }
}
