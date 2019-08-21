<?php
class PrintUtil {

        public      $Empresa_Nombre  = "Proisa S.R.L";
        public      $NoCaracteres;
        private     $connI;
        public      $salto           = "\n";
        public      $dividir         = "";
        private     $config;

        function __construct($config,$tipo_ticket = 'termica')
        {

            $this->config = $config;
            if($tipo_ticket =='movil'){
                $this->NoCaracteres = 30;
            }else{
                $this->NoCaracteres = 40;
            }

            $this->dividir	= $this->PAlin("--",'cen','-');
        }

        /**
         * @param $header
         * @param $content
         * @param $footer
         * @param $cliente_id
         * @return bool|string
         */
        public function TicketFormatPOS($header,$content,$footer)
        {
            $n_c        = $this->NoCaracteres;
            $salto		= "\n";
            $dividir	= $this->PAlin($n_c,"__",'cen');
            $Encabezado =
                "<h3>".$this->PAlin($this->config->nombre,'cen','','32')."</h3>".
                $this->PAlin($this->config->direccion,'cen','').$salto.
                $this->PAlin($this->config->direccion2,'cen','').$salto.
                $this->PAlin($this->config->numero,'cen','').$salto.
                $this->PAlin(' ','cen','_').
                $dividir.
                $header
                .$salto;
            //echo $Encabezado;
            $footer1 =
                $salto.
                $salto.
                $salto.
                $salto."".
                $this->PAlin(" ",'cen','*').$salto.
                $this->PAlin("Gracias por preferirnos! ",'cen','').$salto.
                // $this->PAlin("www.midasred.do",'cen','').$salto.
                // $dividir.$salto.
                $footer;
            if($content){
                $ticket	= $Encabezado.$content.$footer1;
                return $ticket;
            }else{
                return false;
            }
        }


        /**
         * @param array $dat
         * @param string $x_id
         * @return bool|string
         */
        public function FormatTicketPAGO(array $dat, $x_id ='')
        {

            $salto      = "\n";
            $dividir	= $salto.$this->PAlin("__",'cen','_').$salto;
            $ticket     = "";
            if($dat['titulo1']){
                $ticket     .= $this->PAlin($dat['titulo1'],'cen',' ')
                                .$salto.$salto;
            }else{
                $ticket     .= $this->PAlin($dat['titulo1'],'cen',' ').$salto;
            }

            $ticket .= $this->PAlin2Colum('ORDEN #: '.$dat['orden'],'MESA: <b>'.$dat['mesa'].'</b>', ' ').$salto;
            $ticket .= $this->PAlin2Colum('FECHA: '.$dat['fecha'],'CAJA #:'.$dat['caja'], ' ').$salto;
            $ticket .= $this->PAlin2Colum('TURNO: '.$dat['turno'],'APERTURA: '.$dat['apertura'], ' ').$salto;
            $ticket .= $this->PAlin('CAMARERO: '.$dat['camarero'],'izq', ' ').$dividir;
            /*
             * Recorriendo el Detalle:
             */
            $ticket .= $this->PAlin('DESCRIPCION','izq', ' ').$salto;
            $ticket .= $this->PAlin3Colum('CANTIDAD','PRECIO',' TOTAL',' ').$dividir;
            foreach ($dat['detalles'] as $KEY=>$VALOR)
            {
                if(is_array($VALOR))
                {

                   // $this->PAlin3Colum('1','500','1000','.');

                    /*
                     * Detalles con Array multiple.
                     
                    $ticket .="\n".$this->PAlin( ''.strtoupper($KEY),'izq','_').$salto;
                    foreach($VALOR as $SUB_KEY=>$SUB_VALOR)
                    {
                       
                        if(is_array($SUB_VALOR)){
                            $ticket .="\n".$this->PAlin( '- '.strtoupper($SUB_KEY),'izq',' ').$salto;
                            foreach($SUB_VALOR as $SUB_KEY2=>$SUB_VALOR2) {
                                $ticket .= $this->PAlin2Colum( "    ".$SUB_KEY2, $SUB_VALOR2,'.').$salto;
                            }
                        }else{
                            $ticket .= $this->PAlin2Colum( $SUB_KEY, $SUB_VALOR,'.').$salto;
                        }
                    }
                    */
                }else{
                    /*
                     * Detalle sin Array Multiple:
                     */
                   // $ticket .= $this->PAlin2Colum( $KEY, $VALOR,'.').$salto;
                    $ticket .= $this->PAlin('<b>Pechuga de pollo</b>','izq', ' ').$salto;
                    $ticket .= $this->PAlin3Colum('1','500','1000','.');
                }

                if($KEY =='titulo'){
                    $ticket .="\n".$this->PAlin( '_____'.$VALOR.'_____','cen',' ').$salto;
                }
            }

            $ticket .="\n\n".$this->PAlin( '___________','cen',' ').$salto;

            if($dat['nota1']){
                $ticket     .= "".$this->PAlin($dat['nota1'],'cen',' ').$salto;
            }
            // if($dat['nota2'])
            //     $ticket     .= "".$this->PAlin($dat['nota2'],'cen',' ').$salto;

            $ticket2 = $this->TicketFormatPOS("",$ticket,"");
            return $ticket2;
        }

        /**
         * @param $texto
         * @param $alinear
         * @param $relleno
         * @return string
         */
        public function PAlin($texto, $alinear, $relleno = '',$espacio = NULL)
        {
            if(!$relleno){
                $relleno = " ";
            }

            $n_c = $this->NoCaracteres;

            if($espacio){
                $n_c = $espacio;
            }

            if ($alinear == 'der'){
                return str_pad(trim($texto), $n_c, $relleno, STR_PAD_LEFT);
            }else if ($alinear == 'izq'){
                return str_pad(trim($texto), $n_c, $relleno, STR_PAD_RIGHT);
            }else if ($alinear == 'cen') {
                return str_pad(trim($texto), $n_c, $relleno, STR_PAD_BOTH);
            }
        }

        /**
         * @param $textIzquierda
         * @param $textDerecha
         * @param $relleno
         * @return string
         */
        public function PAlin2Colum($textIzquierda,$textDerecha,$relleno)
        {
            $n_c = $this->NoCaracteres;

            if(!$relleno){
                $relleno =". ";
            }

            $lenIzquierda 	= strlen($textIzquierda);
            $lenDerecha 	= strlen($textDerecha);
            $DivCentro		= $n_c - ($lenIzquierda+$lenDerecha);
            $DivCentro2	 	= str_pad("",$DivCentro,$relleno,STR_PAD_LEFT);

            return $textIzquierda.$DivCentro2.$textDerecha;
        }

        public function PAlin3Colum($textIzquierda,$texCentro,$textDerecha,$relleno)
        {
            $n_c = $this->NoCaracteres;

            if(!$relleno){
                $relleno =". ";
            }

            $lenIzquierda 	= strlen($textIzquierda);
            $lenCentro  	= strlen($texCentro);
            $lenDerecha 	= strlen($textDerecha);
            $DivCentro		= $n_c - ($lenIzquierda+$lenCentro+$lenDerecha);
            $DivCentro2	 	= str_pad("",$DivCentro/2,$relleno,STR_PAD_LEFT);
            
            return $textIzquierda.$DivCentro2.$texCentro.$DivCentro2.$textDerecha;
        }

}
