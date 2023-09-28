<?php

namespace App\Controllers\Adm;

use App\Controllers\BaseController;
use App\Entities\Extra;

class Extras extends BaseController
{
    private $extraModel;

    public function __construct()
    {
        $this->extraModel = new \App\Models\ExtraModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Listando os extras de produtos',
            'extras' => $this->extraModel->withDeleted(true)->paginate(10),
            'pager' => $this->extraModel->pager,
        ];

        return view('adm/Extras/index', $data);
    }

    public function search()
    {
        if (!$this->request->isAJAX()) {
            exit('Página não encontrada!');
        }
        $extras = $this->extraModel->search($this->request->getGet('term'));

        $return = []; // variavel de retorno

        foreach ($extras as $extra) {
            $data['id'] = $extra->id;
            $data['value'] = $extra->name;
            $return[] = $data;
        }

        return $this->response->setJSON($return);
    }

    /**
     * Criar uma nova Extra (Additional)
     *
     * @param int $id
     *
     * @return object $Extra
     */
    public function create()
    {
        $extra = new Extra();

        // dd($extra);

        $data = [
         'title' => "Cadastrando novo Extra: $extra->name",
         'extra' => $extra,
        ];

        return view('adm/Extras/create', $data);
    }

    /**
     * Cadastrar/registrar os dados do formulário.
     */
    public function register()
    {
        // $this->request->getMethod() == 'post') deprecated
        if ($this->request->getPost()) {
            $extra = new Extra($this->request->getPost());

            if ($this->extraModel->save($extra)) {
                return redirect()->to(site_url('adm/extras/show/'.$this->extraModel->getInsertID()))
                                ->with('success', "Extra <strong>'$extra->name'</strong>, cadatrado com sucesso");
            } else {
                return redirect()->back()->with('errors_model', $this->extraModel->errors())
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
     * Apresenta na tela o extra selecionado.
     *
     * @param int $id
     *
     * @return object $extra
     */
    public function show($id = null)
    {
        $extra = $this->findExtraOr404($id);

        // dd($extra);

        $data = [
         'title' => "Detalhe do Extra: $extra->name",
         'extra' => $extra,
        ];

        return view('adm/Extras/show', $data);
    }

     /**
      * Pesquisa extra no banco de dados.
      *
      * @param int $id
      *
      * @return object $extra
      */
     private function findExtraOr404(int $id = null)
     {
         // ->withDeleted(true) Pesquisar os extras deletados
         if (!$id || !$extra = $this->extraModel->withDeleted(true)->where('id', $id)->first()) {
             throw \CodeIgniter\Exceptions\PageNotFoundException::ForPageNotFound("Não encontramos o extra $id");
         }

         return $extra;
     }

    /**
     * Editar o Extra na tela.
     *
     * @param int $id
     *
     * @return object $extra
     */
    public function edit($id = null)
    {
        $extra = $this->findExtraOr404($id);

        if ($extra->deleted_at != null) {
            return redirect()->back()->with('info', "O Extra, <strong>'$extra->name'</strong> encontra-se excluído. Portanto, não é possivel editá-lo.");
        }

        // dd($extra);

        $data = [
         'title' => "Editando o extra: $extra->name",
         'extra' => $extra,
        ];

        return view('adm/Extras/edit', $data);
    }

    /**
     * Atualizar os dados do formulário.
     */
    public function update($id = null)
    {
        // $this->request->getMethod() == 'post') deprecated
        if ($this->request->getPost()) {
            $extra = $this->findExtraOr404($id);

            if ($extra->deleted_at != null) {
                return redirect()->back()->with('info', "O extra, <strong>'$extra->name'</strong> encontra-se excluído. Portanto não é possivel exclui-lo");
            }

            // Preparar os dados e enviar para o database
            $extra->fill($this->request->getPost());

            // Não houve alteração nos dados, não atualiza o database
            if (!$extra->hasChanged()) {
                return redirect()->back()->with('warning', 'Não há dados para atualizar!');
            }

            if ($this->extraModel->save($extra)) {
                return redirect()->to(site_url("adm/extras/show/$extra->id"))
                                ->with('success', "Extra <strong>'$extra->name'</strong>, atualizado com sucesso");
            } else {
                return redirect()->back()->with('errors_model', $this->extraModel->errors())
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
     * Excluir o usuário no database (softdelete).
     *
     * @param int $id
     *
     * @return object $user
     */
    public function excluir($id = null)
    {
        $extra = $this->findExtraOr404($id);

        if ($extra->deleted_at != null) {
            return redirect()->back()->with('info', "O extra, <strong>'$extra->name'</strong> já encontra-se excluída.");
        }

        if ($this->request->getMethod() === 'post') {
            $this->extraModel->delete($id);

            return redirect()->to(site_url('adm/extras'))
                             ->with('success', "Extra $extra->name, excluído com sucesso");
        }

        $data = [
         'title' => "Excluindo o extra: $extra->name",
         'extra' => $extra,
        ];

        return view('adm/Extras/excluir', $data);
    }

    /**
     * Desfazer a exclusão do usuário no database (softdelete).
     *
     * @param int $id
     *
     * @return object $user
     */
    public function undoDelete($id = null)
    {
        $extra = $this->findExtraOr404($id);

        if ($extra->deleted_at == null) {
            return redirect()->back()->with('info', 'Apenas extras excluídos podem ser recuperados!');
        }

        if ($this->extraModel->undoDelete($id)) {
            return redirect()->back()->with('success', 'Exclusão desfeita com sucesso.');
        } else {
            return redirect()->back()
                             ->with('errors_model', $this->extraModel->errors())
                             ->with('info', 'Por favor verifique os erros abaixo')
                             ->withInput();
        }
    }
}
