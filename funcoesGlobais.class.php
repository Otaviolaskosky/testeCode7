<?php

class funcoesGlobais {

    /**
     * Função para formatar o valor em moedas locais
     * 
     * @param type $formato
     * @param type $valor
     * @return type
     */
    public function money_format($formato, $valor) {

        if (setlocale(LC_MONETARY, 0) == 'C') {
            return number_format($valor, 2);
        }

        $locale = localeconv();

        $regex = '/^' . // Inicio da Expressao 
                '%' . // Caractere % 
                '(?:' . // Inicio das Flags opcionais 
                '\=([\w\040])' . // Flag =f 
                '|' .
                '([\^])' . // Flag ^ 
                '|' .
                '(\+|\()' . // Flag + ou ( 
                '|' .
                '(!)' . // Flag ! 
                '|' .
                '(-)' . // Flag - 
                ')*' . // Fim das flags opcionais 
                '(?:([\d]+)?)' . // W  Largura de campos 
                '(?:#([\d]+))?' . // #n Precisao esquerda 
                '(?:\.([\d]+))?' . // .p Precisao direita 
                '([in%])' . // Caractere de conversao 
                '$/';             // Fim da Expressao 

        if (!preg_match($regex, $formato, $matches)) {
            trigger_error('Formato invalido: ' . $formato, E_USER_WARNING);
            return $valor;
        }

        $opcoes = array(
            'preenchimento' => ($matches[1] !== '') ? $matches[1] : ' ',
            'nao_agrupar' => ($matches[2] == '^'),
            'usar_sinal' => ($matches[3] == '+'),
            'usar_parenteses' => ($matches[3] == '('),
            'ignorar_simbolo' => ($matches[4] == '!'),
            'alinhamento_esq' => ($matches[5] == '-'),
            'largura_campo' => ($matches[6] !== '') ? (int) $matches[6] : 0,
            'precisao_esq' => ($matches[7] !== '') ? (int) $matches[7] : false,
            'precisao_dir' => ($matches[8] !== '') ? (int) $matches[8] : $locale['int_frac_digits'],
            'conversao' => $matches[9]
        );

        if ($opcoes['usar_sinal'] && $locale['n_sign_posn'] == 0) {
            $locale['n_sign_posn'] = 1;
        } elseif ($opcoes['usar_parenteses']) {
            $locale['n_sign_posn'] = 0;
        }
        if ($opcoes['precisao_dir']) {
            $locale['frac_digits'] = $opcoes['precisao_dir'];
        }
        if ($opcoes['nao_agrupar']) {
            $locale['mon_thousands_sep'] = '';
        }

        $tipo_sinal = $valor >= 0 ? 'p' : 'n';
        if ($opcoes['ignorar_simbolo']) {
            $simbolo = '';
        } else {
            $simbolo = $opcoes['conversao'] == 'n' ? $locale['currency_symbol'] : $locale['int_curr_symbol'];
        }
        $numero = number_format(abs($valor), $locale['frac_digits'], $locale['mon_decimal_point'], $locale['mon_thousands_sep']);


        $sinal = $valor >= 0 ? $locale['positive_sign'] : $locale['negative_sign'];
        $simbolo_antes = $locale[$tipo_sinal . '_cs_precedes'];

        $espaco1 = $locale[$tipo_sinal . '_sep_by_space'] == 1 ? ' ' : '';

        $espaco2 = $locale[$tipo_sinal . '_sep_by_space'] == 2 ? ' ' : '';

        $formatado = '';
        switch ($locale[$tipo_sinal . '_sign_posn']) {
            case 0:
                if ($simbolo_antes) {
                    $formatado = '(' . $simbolo . $espaco1 . $numero . ')';
                } else {
                    $formatado = '(' . $numero . $espaco1 . $simbolo . ')';
                }
                break;
            case 1:
                if ($simbolo_antes) {
                    $formatado = $sinal . $espaco2 . $simbolo . $espaco1 . $numero;
                } else {
                    $formatado = $sinal . $numero . $espaco1 . $simbolo;
                }
                break;
            case 2:
                if ($simbolo_antes) {
                    $formatado = $simbolo . $espaco1 . $numero . $sinal;
                } else {
                    $formatado = $numero . $espaco1 . $simbolo . $espaco2 . $sinal;
                }
                break;
            case 3:
                if ($simbolo_antes) {
                    $formatado = $sinal . $espaco2 . $simbolo . $espaco1 . $numero;
                } else {
                    $formatado = $numero . $espaco1 . $sinal . $espaco2 . $simbolo;
                }
                break;
            case 4:
                if ($simbolo_antes) {
                    $formatado = $simbolo . $espaco2 . $sinal . $espaco1 . $numero;
                } else {
                    $formatado = $numero . $espaco1 . $simbolo . $espaco2 . $sinal;
                }
                break;
        }

        if ($opcoes['largura_campo'] > 0 && strlen($formatado) < $opcoes['largura_campo']) {
            $alinhamento = $opcoes['alinhamento_esq'] ? STR_PAD_RIGHT : STR_PAD_LEFT;
            $formatado = str_pad($formatado, $opcoes['largura_campo'], $opcoes['preenchimento'], $alinhamento);
        }

        return $formatado;
    }

}
