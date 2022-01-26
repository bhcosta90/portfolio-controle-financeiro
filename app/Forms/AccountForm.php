<?php

namespace App\Forms;

use App\Models\Account;
use Kris\LaravelFormBuilder\Form;

class AccountForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => __('Account name'),
            'rules' => 'required|min:3|max:150',
        ]);

        if (empty(request()->account)) {
            $this->add('value', 'number', [
                'label' => __('Value'),
                'attrs' => ['step' => '0.01'],
                'rules' => ['numeric', 'min:-9999999999', 'max:9999999999']
            ]);
        }

        $this->add('bank_code', 'select', [
            'label' => __('Code bank'),
            'choices' => $data = $this->transformsInArrayBanks(),
            'rules' => 'required|in:' . implode(',', array_keys($data)),
            'empty_value' => __('Select') . '...'
        ]);

        $this->add('bank_account', 'text', [
            'label' => __('Account bank'),
            'rules' => 'required|min:3|max:20',
        ]);

        $this->add('bank_agency', 'text', [
            'label' => __('Account agency'),
            'rules' => 'required|min:3|max:20',
        ]);

        $this->add('bank_digit', 'text', [
            'label' => __('Digit account'),
            'rules' => 'required|max:2',
        ]);
    }

    private function transformsInArrayBanks()
    {
        $ret = [];

        foreach($this->getArrayBanks() as $k => $rs){
            $ret[$k] = sprintf("%s - %s", $k, $rs);
        }

        return $ret;
    }

    private function getArrayBanks()
    {
        $var_banco = [];
        $var_banco['0246'] = 'Banco ABC Brasil S.A.';
        $var_banco['0748'] = 'Banco Cooperativo Sicredi S.A.';
        $var_banco['0117'] = 'Advanced Cc Ltda';
        $var_banco['0121'] = 'Banco Agibank S.A.';
        $var_banco['0172'] = 'Albatross Ccv S.A';
        $var_banco['0188'] = 'Ativa Investimentos S.A';
        $var_banco['0280'] = 'Avista S.A. Crédito, Financiamento e Investimento';
        $var_banco['0080'] = 'B&T Cc Ltda';
        $var_banco['0654'] = 'Banco A.J.Renner';
        $var_banco['0246'] = 'Banco ABC Brasil S.A.';
        $var_banco['0075'] = 'Banco ABN AMRO S.A';
        $var_banco['0121'] = 'Banco Agibank S.A.';
        $var_banco['0025'] = 'Banco Alfa S.A.';
        $var_banco['0641'] = 'Banco Alvorada S.A.';
        $var_banco['0065'] = 'Banco Andbank (Brasil) S.A.';
        $var_banco['0213'] = 'Banco Arbi S.A.';
        $var_banco['0096'] = 'Banco B3 S.A.';
        $var_banco['0024'] = 'Banco BANDEPE S.A.';
        $var_banco['0318'] = 'Banco BMG S.A.';
        $var_banco['0752'] = 'Banco BNP Paribas Brasil S.A.';
        $var_banco['0107'] = 'Banco BOCOM BBM S.A.';
        $var_banco['0063'] = 'Banco Bradescard S.A.';
        $var_banco['0036'] = 'Banco Bradesco BBI S.A.';
        $var_banco['0122'] = 'Banco Bradesco BERJ S.A.';
        $var_banco['0204'] = 'Banco Bradesco Cartões S.A.';
        $var_banco['0394'] = 'Banco Bradesco Financiamentos S.A.';
        $var_banco['0237'] = 'Banco Bradesco S.A.';
        $var_banco['0218'] = 'Banco BS2 S.A.';
        $var_banco['0208'] = 'Banco BTG Pactual S.A.';
        $var_banco['0336'] = 'Banco C6 S.A – C6 Bank';
        $var_banco['0473'] = 'Banco Caixa Geral – Brasil S.A.';
        $var_banco['0412'] = 'Banco Capital S.A.';
        $var_banco['0040'] = 'Banco Cargill S.A.';
        $var_banco['0368'] = 'Banco Carrefour';
        $var_banco['0266'] = 'Banco Cédula S.A.';
        $var_banco['0739'] = 'Banco Cetelem S.A.';
        $var_banco['0233'] = 'Banco Cifra S.A.';
        $var_banco['0745'] = 'Banco Citibank S.A.';
        $var_banco['0241'] = 'Banco Clássico S.A.';
        $var_banco['0756'] = 'Banco Cooperativo do Brasil S.A. – BANCOOB';
        $var_banco['0748'] = 'Banco Cooperativo Sicredi S.A.';
        $var_banco['0222'] = 'Banco Credit Agricole Brasil S.A.';
        $var_banco['0505'] = 'Banco Credit Suisse (Brasil) S.A.';
        $var_banco['0069'] = 'Banco Crefisa S.A.';
        $var_banco['0003'] = 'Banco da Amazônia S.A.';
        $var_banco['0083'] = 'Banco da China Brasil S.A.';
        $var_banco['0707'] = 'Banco Daycoval S.A.';
        $var_banco['0051'] = 'Banco de Desenvolvimento do Espírito Santo S.A.';
        $var_banco['0300'] = 'Banco de La Nacion Argentina';
        $var_banco['0495'] = 'Banco de La Provincia de Buenos Aires';
        $var_banco['0494'] = 'Banco de La Republica Oriental del Uruguay';
        $var_banco['0335'] = 'Banco Digio S.A';
        $var_banco['0001'] = 'Banco do Brasil S.A.';
        $var_banco['0047'] = 'Banco do Estado de Sergipe S.A.';
        $var_banco['0037'] = 'Banco do Estado do Pará S.A.';
        $var_banco['0041'] = 'Banco do Estado do Rio Grande do Sul S.A.';
        $var_banco['0004'] = 'Banco do Nordeste do Brasil S.A.';
        $var_banco['0196'] = 'Banco Fair Corretora de Câmbio S.A';
        $var_banco['0265'] = 'Banco Fator S.A.';
        $var_banco['0224'] = 'Banco Fibra S.A.';
        $var_banco['0626'] = 'Banco Ficsa S.A.';
        $var_banco['0094'] = 'Banco Finaxis S.A.';
        $var_banco['0612'] = 'Banco Guanabara S.A.';
        $var_banco['0012'] = 'Banco Inbursa S.A.';
        $var_banco['0604'] = 'Banco Industrial do Brasil S.A.';
        $var_banco['0653'] = 'Banco Indusval S.A.';
        $var_banco['0077'] = 'Banco Inter S.A.';
        $var_banco['0249'] = 'Banco Investcred Unibanco S.A.';
        $var_banco['0184'] = 'Banco Itaú BBA S.A.';
        $var_banco['0029'] = 'Banco Itaú Consignado S.A.';
        $var_banco['0479'] = 'Banco ItauBank S.A';
        $var_banco['0376'] = 'Banco J. P. Morgan S.A.';
        $var_banco['0074'] = 'Banco J. Safra S.A.';
        $var_banco['0217'] = 'Banco John Deere S.A.';
        $var_banco['0076'] = 'Banco KDB S.A.';
        $var_banco['0757'] = 'Banco KEB HANA do Brasil S.A.';
        $var_banco['0600'] = 'Banco Luso Brasileiro S.A.';
        $var_banco['0243'] = 'Banco Máxima S.A.';
        $var_banco['0720'] = 'Banco Maxinvest S.A.';
        $var_banco['0380'] = 'Banco Picpay Servicos S.A.';
        $var_banco['0389'] = 'Banco Mercantil de Investimentos S.A.';
        $var_banco['0389'] = 'Banco Mercantil do Brasil S.A.';
        $var_banco['0370'] = 'Banco Mizuho do Brasil S.A.';
        $var_banco['0746'] = 'Banco Modal S.A.';
        $var_banco['0066'] = 'Banco Morgan Stanley S.A.';
        $var_banco['0456'] = 'Banco MUFG Brasil S.A.';
        $var_banco['0007'] = 'Banco Nacional de Desenvolvimento Econômico e Social – BNDES';
        $var_banco['0169'] = 'Banco Olé Bonsucesso Consignado S.A.';
        $var_banco['0111'] = 'Banco Oliveira Trust Dtvm S.A';
        $var_banco['0079'] = 'Banco Original do Agronegócio S.A.';
        $var_banco['0212'] = 'Banco Original S.A.';
        $var_banco['0712'] = 'Banco Ourinvest S.A.';
        $var_banco['0623'] = 'Banco PAN S.A.';
        $var_banco['0611'] = 'Banco Paulista S.A.';
        $var_banco['0643'] = 'Banco Pine S.A.';
        $var_banco['0658'] = 'Banco Porto Real de Investimentos S.A.';
        $var_banco['0747'] = 'Banco Rabobank International Brasil S.A.';
        $var_banco['0633'] = 'Banco Rendimento S.A.';
        $var_banco['0741'] = 'Banco Ribeirão Preto S.A.';
        $var_banco['0120'] = 'Banco Rodobens S.A.';
        $var_banco['0422'] = 'Banco Safra S.A.';
        $var_banco['0033'] = 'Banco Santander (Brasil) S.A.';
        $var_banco['0743'] = 'Banco Semear S.A.';
        $var_banco['0754'] = 'Banco Sistema S.A.';
        $var_banco['0630'] = 'Banco Smartbank S.A.';
        $var_banco['0366'] = 'Banco Société Générale Brasil S.A.';
        $var_banco['0637'] = 'Banco Sofisa S.A.';
        $var_banco['0464'] = 'Banco Sumitomo Mitsui Brasileiro S.A.';
        $var_banco['0082'] = 'Banco Topázio S.A.';
        $var_banco['0634'] = 'Banco Triângulo S.A.';
        $var_banco['0018'] = 'Banco Tricury S.A.';
        $var_banco['0655'] = 'Banco Votorantim S.A.';
        $var_banco['0610'] = 'Banco VR S.A.';
        $var_banco['0119'] = 'Banco Western Union do Brasil S.A.';
        $var_banco['0124'] = 'Banco Woori Bank do Brasil S.A.';
        $var_banco['0348'] = 'Banco Xp S/A';
        $var_banco['0081'] = 'BancoSeguro S.A.';
        $var_banco['0021'] = 'BANESTES S.A. Banco do Estado do Espírito Santo';
        $var_banco['0755'] = 'Bank of America Merrill Lynch Banco Múltiplo S.A.';
        $var_banco['0268'] = 'Barigui Companhia Hipotecária';
        $var_banco['0250'] = 'BCV – Banco de Crédito e Varejo S.A.';
        $var_banco['0144'] = 'BEXS Banco de Câmbio S.A.';
        $var_banco['0253'] = 'Bexs Corretora de Câmbio S/A';
        $var_banco['0134'] = 'Bgc Liquidez Dtvm Ltda';
        $var_banco['0017'] = 'BNY Mellon Banco S.A.';
        $var_banco['0301'] = 'Bpp Instituição De Pagamentos S.A';
        $var_banco['0126'] = 'BR Partners Banco de Investimento S.A.';
        $var_banco['0070'] = 'BRB – Banco de Brasília S.A.';
        $var_banco['0092'] = 'Brickell S.A. Crédito, Financiamento e Investimento';
        $var_banco['0173'] = 'BRL Trust Distribuidora de Títulos e Valores Mobiliários S.A.';
        $var_banco['0142'] = 'Broker Brasil Cc Ltda';
        $var_banco['0292'] = 'BS2 Distribuidora de Títulos e Valores Mobiliários S.A.';
        $var_banco['0011'] = 'C.Suisse Hedging-Griffo Cv S.A (Credit Suisse)';
        $var_banco['0104'] = 'Caixa Econômica Federal';
        $var_banco['0288'] = 'Carol Distribuidora de Títulos e Valor Mobiliários Ltda';
        $var_banco['0130'] = 'Caruana Scfi';
        $var_banco['0159'] = 'Casa Credito S.A';
        $var_banco['0016'] = 'Ccm Desp Trâns Sc E Rs';
        $var_banco['0089'] = 'Ccr Reg Mogiana';
        $var_banco['0114'] = 'Central Cooperativa De Crédito No Estado Do Espírito Santo';
        $var_banco['0114-7'] = 'Central das Cooperativas de Economia e Crédito Mútuo doEstado do Espírito Santo Ltda.';
        $var_banco['0320'] = 'China Construction Bank (Brasil) Banco Múltiplo S.A.';
        $var_banco['0477'] = 'Citibank N.A.';
        $var_banco['0180'] = 'Cm Capital Markets Cctvm Ltda';
        $var_banco['0127'] = 'Codepe Cvc S.A';
        $var_banco['0163'] = 'Commerzbank Brasil S.A. – Banco Múltiplo';
        $var_banco['0060'] = 'Confidence Cc S.A';
        $var_banco['0085'] = 'Coop Central Ailos';
        $var_banco['0097'] = 'Cooperativa Central de Crédito Noroeste Brasileiro Ltda.';
        // $var_banco['085-x'] = 'Cooperativa Central de Crédito Urbano-CECRED';
        // $var_banco['090-2'] = 'Cooperativa Central de Economia e Crédito Mutuo – SICOOB UNIMAIS';
        // $var_banco['087-6'] = 'Cooperativa Central de Economia e Crédito Mútuo das Unicredsde Santa Catarina e Paraná';
        // $var_banco['089-2'] = 'Cooperativa de Crédito Rural da Região da Mogiana';
        $var_banco['0286'] = 'Cooperativa de Crédito Rural De Ouro';
        $var_banco['0279'] = 'Cooperativa de Crédito Rural de Primavera Do Leste';
        $var_banco['0273'] = 'Cooperativa de Crédito Rural de São Miguel do Oeste – Sulcredi/São Miguel';
        $var_banco['0098'] = 'Credialiança Ccr';
        // $var_banco['098-1'] = 'CREDIALIANÇA COOPERATIVA DE CRÉDITO RURAL';
        $var_banco['0010'] = 'Credicoamo';
        $var_banco['0133'] = 'Cresol Confederação';
        $var_banco['0182'] = 'Dacasa Financeira S/A';
        $var_banco['0707'] = 'Banco Daycoval S.A.';
        $var_banco['0487'] = 'Deutsche Bank S.A. – Banco Alemão';
        $var_banco['0140'] = 'Easynvest – Título Cv S.A';
        $var_banco['0149'] = 'Facta S.A. Cfi';
        $var_banco['0285'] = 'Frente Corretora de Câmbio Ltda.';
        $var_banco['0278'] = 'Genial Investimentos Corretora de Valores Mobiliários S.A.';
        $var_banco['0138'] = 'Get Money Cc Ltda';
        $var_banco['0064'] = 'Goldman Sachs do Brasil Banco Múltiplo S.A.';
        $var_banco['0177'] = 'Guide Investimentos S.A. Corretora de Valores';
        $var_banco['0146'] = 'Guitta Corretora de Câmbio Ltda';
        $var_banco['0078'] = 'Haitong Banco de Investimento do Brasil S.A.';
        $var_banco['0062'] = 'Hipercard Banco Múltiplo S.A.';
        $var_banco['0189'] = 'HS Financeira S/A Crédito, Financiamento e Investimentos';
        $var_banco['0269'] = 'HSBC Brasil S.A. – Banco de Investimento';
        $var_banco['0271'] = 'IB Corretora de Câmbio, Títulos e Valores Mobiliários S.A.';
        $var_banco['0157'] = 'Icap Do Brasil Ctvm Ltda';
        $var_banco['0132'] = 'ICBC do Brasil Banco Múltiplo S.A.';
        $var_banco['0492'] = 'ING Bank N.V.';
        $var_banco['0139'] = 'Intesa Sanpaolo Brasil S.A. – Banco Múltiplo';
        $var_banco['0652'] = 'Itaú Unibanco Holding S.A.';
        $var_banco['0341'] = 'Itaú Unibanco S.A.';
        $var_banco['0488'] = 'JPMorgan Chase Bank, National Association';
        $var_banco['0399'] = 'Kirton Bank S.A. – Banco Múltiplo';
        $var_banco['0293'] = 'Lastro RDV Distribuidora de Títulos e Valores Mobiliários Ltda.';
        $var_banco['0105'] = 'Lecca Crédito, Financiamento e Investimento S/A';
        $var_banco['0145'] = 'Levycam Ccv Ltda';
        $var_banco['0113'] = 'Magliano S.A';
        $var_banco['0323'] = 'Mercado Pago – Conta Do Mercado Livre';
        $var_banco['0128'] = 'MS Bank S.A. Banco de Câmbio';
        $var_banco['0137'] = 'Multimoney Cc Ltda';
        $var_banco['0014'] = 'Natixis Brasil S.A. Banco Múltiplo';
        $var_banco['0191'] = 'Nova Futura Corretora de Títulos e Valores Mobiliários Ltda.';
        $var_banco['0753'] = 'Novo Banco Continental S.A. – Banco Múltiplo';
        $var_banco['0260'] = 'Nu Pagamentos S.A (Nubank)';
        $var_banco['0613'] = 'Omni Banco S.A.';
        $var_banco['0613'] = 'Omni Banco S.A.';
        $var_banco['0290'] = 'Pagseguro Internet S.A';
        $var_banco['0254'] = 'Paraná Banco S.A.';
        $var_banco['0326'] = 'Parati – Crédito Financiamento e Investimento S.A.';
        $var_banco['0194'] = 'Parmetal Distribuidora de Títulos e Valores Mobiliários Ltda';
        $var_banco['0174'] = 'Pernambucanas Financ S.A';
        $var_banco['0100'] = 'Planner Corretora De Valores S.A';
        $var_banco['0125'] = 'Plural S.A. – Banco Múltiplo';
        $var_banco['0093'] = 'Pólocred Scmepp Ltda';
        $var_banco['0108'] = 'Portocred S.A';
        $var_banco['0283'] = 'Rb Capital Investimentos Dtvm Ltda';
        $var_banco['0101'] = 'Renascenca Dtvm Ltda';
        $var_banco['0270'] = 'Sagitur Corretora de Câmbio Ltda.';
        $var_banco['0751'] = 'Scotiabank Brasil S.A. Banco Múltiplo';
        $var_banco['0276'] = 'Senff S.A. – Crédito, Financiamento e Investimento';
        $var_banco['0545'] = 'Senso Ccvm S.A';
        $var_banco['0190'] = 'Servicoop';
        $var_banco['0183'] = 'Socred S.A';
        $var_banco['0299'] = 'Sorocred Crédito, Financiamento e Investimento S.A.';
        $var_banco['0118'] = 'Standard Chartered Bank (Brasil) S/A–Bco Invest.';
        $var_banco['0197'] = 'Stone Pagamentos S.A';
        $var_banco['0340'] = 'Super Pagamentos e Administração de Meios Eletrônicos S.A.';
        $var_banco['0095'] = 'Travelex Banco de Câmbio S.A.';
        $var_banco['0143'] = 'Treviso Corretora de Câmbio S.A.';
        $var_banco['0131'] = 'Tullett Prebon Brasil Cvc Ltda';
        $var_banco['0129'] = 'UBS Brasil Banco de Investimento S.A.';
        // $var_banco['091-4'] = 'Unicred Central do Rio Grande do Sul';
        $var_banco['091'] = 'Unicred Central Rs';
        $var_banco['0136'] = 'Unicred Cooperativa';
        $var_banco['0099'] = 'UNIPRIME Central – Central Interestadual de Cooperativas de Crédito Ltda.';
        $var_banco['0084'] = 'Uniprime Norte do Paraná – Coop de Economia eCrédito Mútuo dos Médicos, Profissionais das Ciências';
        $var_banco['0298'] = 'Vips Cc Ltda';
        $var_banco['0310'] = 'Vortx Distribuidora de Títulos e Valores Mobiliários Ltda';
        $var_banco['0102'] = 'Xp Investimentos S.A';

        return $var_banco;
    }
}
