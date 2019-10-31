<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Adldap\Laravel\Facades\Adldap;
use Carbon\Carbon;
use Uspdev\Replicado\Bempatrimoniado;
use Uspdev\Replicado\Pessoa;

class indexController extends Controller
{
    public function __construct() {
    }

    public function index(){
    
        $ldap_computers = Adldap::search()->computers()->get();
        
        $computers = [];
        foreach($ldap_computers as $computer){
            $line = [];

            $hostname = $computer->getDnsHostName();
            $hostname = str_replace(".smbdomain.fflch.usp.br", "", $hostname);
            $hostname = str_replace("-", "", $hostname);
            if (strstr($hostname, 'sambadc') == true) {
                continue;
            }
            $line['hostname'] = $hostname;

            $line['created'] = Carbon::createFromFormat('YmdHis\.0\Z', $computer->getCreatedAt())
                               ->setTimezone('America/Sao_paulo')
                               ->format('d/m/Y H:m');

            $line['os'] = $computer->getOperatingSystem() . '-' . 
                          $computer->getOperatingSystemVersion() . " ";

            if($this->is_integerable( $hostname )) {
                $pat = Bempatrimoniado::dump($hostname);
                if($pat){
                    $line['setor']       = $pat["sglcendsp"];
                    $line['status']      = $pat["stabem"];
                    $line['marca']       = $pat["epfmarpat"];
                    $line['modelo']      = $pat["modpat"];
                    $line['tipo']        = $pat["tippat"];
                    $line['incorporado'] = $pat["dtadocinppat"];
                    $pessoa = Pessoa::dump($pat["codpes"]);
                    $line['responsavel']  =  $pessoa['codpes'] . ' - ' . $pessoa['nompes']; 

                    /*busco ip*/
                    $line['ip']       = "não";
                }
            }
            array_push($computers,$line);
        }
        //dd($computers);
        return view('index',compact('computers'));
    }

    private function is_integerable( $v ){
      return is_numeric($v) && +$v === (int)(+$v);
    }
}
