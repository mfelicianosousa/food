<?php

namespace App\Controllers\Adm;

use App\Controllers\BaseController;
use App\Entities\PaymentMethod;

class PaymentMethods extends BaseController
{
    private $paymentMethodsModel;

    public function __construct()
    {
        $this->paymentMethodsModel = new \App\Models\PaymentMethodsModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Listando as formas de pagamento',
            'paymentMethods' => $this->paymentMethodsModel->withDeleted(true)->paginate(10),
            'pager' => $this->paymentMethodsModel->pager,
        ];

        return view('Adm/PaymentMethods/index', $data);
    }

    public function show($id = null)
    {
        $paymentMethod = $this->findPaymentMethodsOr404($id);

        $data = [
            'title' => "Detalhando a Forma de Pagamento $paymentMethod->name",
            'paymentMethod' => $paymentMethod,
        ];

        return view('Adm/PaymentMethods/show', $data);
    }

    public function edit($id = null)
    {
        $paymentMethod = $this->findPaymentMethodsOr404($id);

        $data = [
            'title' => "Forma de Pagamento $paymentMethod->name",
            'paymentMethod' => $paymentMethod,
        ];

        return view('Adm/PaymentMethods/edit', $data);
    }

    public function update($id = null)
    {
        if ($this->request->getMethod() === 'post') {
            $paymentMethod = $this->findPaymentMethodsOr404($id);
            $paymentMethod->fill($this->request->getPost());
            if (!$paymentMethod->hasChanged()) {
                return redirect()->back()->with('info', 'Não há dados para atualizar');
            }

            if ($this->paymentMethodsModel->save($paymentMethod)) {
                return redirect()->to(site_url("adm/payment/show/$paymentMethod->id"))
                                ->with('success', "Forma de Pagamento <strong>$paymentMethod->name'</strong>, atualizada com sucesso");
            } else {
                return redirect()->back()->with('errors_model', $this->paymentMethodsModel->errors())
                                        ->with('info', 'Por favor verifique os erros abaixo')
                                        ->withInput();
            }
        } else {
            return redirect()->back();
        }
    }

    public function create()
    {
        $paymentMethod = new PaymentMethod();

        $data = [
            'title' => 'Forma de Pagamento',
            'paymentMethod' => $paymentMethod,
        ];

        return view('Adm/PaymentMethods/create', $data);
    }

    /**
     * Cadastrar/registrar os dados do formulário.
     */
    public function register()
    {
        if ($this->request->getMethod() == 'post') {
            $paymentMethod = new PaymentMethod($this->request->getPost());

            if ($this->paymentMethodsModel->save($paymentMethod)) {
                return redirect()->to(site_url('adm/payment/show/'.$this->paymentMethodsModel->getInsertID()))
                                ->with('success', "Forma de pagamento <strong>'$paymentMethod->name'</strong>, cadatrado com sucesso");
            } else {
                return redirect()->back()->with('errors_model', $this->paymentMethodsModel->errors())
                                        ->with('info', 'Por favor verifique os erros abaixo')
                                        ->withInput();
            }
        } else {
            /* Não é post */
            // return redirect()->back()->with('info','Por favor envie um POST');
            return redirect()->back();
        }
    }

    /**
     * Function Ajax search.
     *
     * @return object
     */
    public function search()
    {
        if (!$this->request->isAJAX()) {
            exit('Página não encontrada!');
        }
        $paymentMethods = $this->paymentMethodsModel->search($this->request->getGet('term'));
        echo '<pre>';
        var_dump($paymentMethods);

        $return = []; // variavel de retorno

        foreach ($paymentMethods as $paymentMethod) {
            $data['id'] = $paymentMethod->id;
            $data['value'] = $paymentMethod->name;
            $return[] = $data;
        }
        echo '<pre>';
        var_dump($return);
        exit;

        return $this->response->setJSON($return);
    }

    /**
     * Pesquisa extra no banco de dados.
     *
     * @param int $id
     *
     * @return object $paymentMethod
     */
    private function findPaymentMethodsOr404(int $id = null)
    {
        if (!$id || !$paymentMethods = $this->paymentMethodsModel->withDeleted(true)->where('id', $id)->first()) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::ForPageNotFound("Não encontramos a forma de Pagamento $id");
        }

        return $paymentMethods;
    }

    /**
     * Excluir a forma de pagamento no database (softdelete).
     *
     * @param int $id
     *
     * @return object 
     */
    public function delete($id = null)
    {
       
        $paymentMethod = $this->findPaymentMethodsOr404($id);

        if ($paymentMethod->deleted_at != null) {
            return redirect()->back()->with('info', "A forma de pagamento, <strong>'$paymentMethod->name'</strong> já encontra-se excluída.");
        }

        if ($this->request->getMethod() === 'post') {
            $this->paymentMethodsModel->delete($id);

            return redirect()->to(site_url('adm/payment'))
                             ->with('success', "Forma de pagamento, $paymentMethod->name, excluída com sucesso");
        }

        $data = [
         'title' => "Excluindo a forma de pagamento: $paymentMethod->name",
         'paymentMethod' => $paymentMethod,
        ];

        return view('Adm/PaymentMethods/delete', $data);
    }

    /**
     * Desfazer a exclusão no database (softdelete).
     *
     * @param int $id
     *
     * @return object $user
     */
    public function undoDelete($id = null)
    {
   
        $paymentMethod = $this->findPaymentMethodsOr404($id);

        if ($paymentMethod->deleted_at == null) {
            return redirect()->back()->with('info', 'Apenas forma de pagamento excluídas podem ser recuperados!');
        }

        if ($this->paymentMethodsModel->undoDelete($id)) {
            return redirect()->back()->with('success', 'Exclusão desfeita com sucesso.');
        } else {
            return redirect()->back()
                             ->with('errors_model', $this->paymentMethodsModel->errors())
                             ->with('info', 'Por favor verifique os erros abaixo')
                             ->withInput();
        }
    }
}
