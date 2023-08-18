<?php

namespace App\Controllers\Adm;

use App\Controllers\BaseController;
use App\Entities\Measure;

class Measurements extends BaseController
{
    private $measureModel;

    public function __construct()
    {
        $this->measureModel = new \App\Models\MeasureModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Medidas de Produtos',
            'measurements' => $this->measureModel->withDeleted(true)->paginate(10),
            'pager' => $this->measureModel->pager,
        ];

        return view('adm/measurements/index', $data);
    }

    public function search()
    {
        if (!$this->request->isAJAX()) {
            exit('Página não encontrada!');
        }
        $measurements = $this->measureModel->search($this->request->getGet('term'));

        $return = []; // variavel de retorno

        foreach ($measurements as $measure) {
            $data['id'] = $measure->id;
            $data['value'] = $measure->name;
            $return[] = $data;
        }

        return $this->response->setJSON($return);
    }

    /**
     * Apresenta na tela a medida selecionado.
     *
     * @param int $id
     *
     * @return object $measure
     */
    public function show($id = null)
    {
        $measure = $this->findMeasureOr404($id);

        // dd($extra);

        $data = [
         'title' => "Detalhe da Medida: $measure->name",
         'measure' => $measure,
        ];

        return view('adm/Measurements/show', $data);
    }

    /**
     * Criar nova medida.
     *
     * @param int $id
     *
     * @return object $measure
     */
    public function create()
    {
        $measure = new Measure();

        // dd($extra);

        $data = [
         'title' => "Cadastrando nova medida: $measure->name",
         'measure' => $measure,
        ];

        return view('adm/Measurements/create', $data);
    }

    /**
     * Cadastrar/registrar os dados do formulário.
     */
    public function register()
    {
        // $this->request->getMethod() == 'post') deprecated
        if ($this->request->getPost()) {
            // Entity Measure
            $measure = new Measure($this->request->getPost());

            if ($this->measureModel->save($measure)) {
                return redirect()->to(site_url('adm/measurements/show/'.$this->measureModel->getInsertID()))
                                ->with('success', "Medidas <strong>'$measure->name'</strong>, cadatrada com sucesso");
            } else {
                return redirect()->back()->with('errors_model', $this->measureModel->errors())
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
     * Apresenta na tela a medida selecionado.
     *
     * @param int $id
     *
     * @return object $measure
     */
    public function edit($id = null)
    {
        $measure = $this->findMeasureOr404($id);

        // dd($extra);

        $data = [
         'title' => "Editando a Medida: $measure->name",
         'measure' => $measure,
        ];

        return view('adm/Measurements/edit', $data);
    }

    /**
     * Atualizar os dados do formulário.
     */
    public function update($id = null)
    {
        // $this->request->getMethod() == 'post') deprecated
        if ($this->request->getPost()) {
            $measure = $this->findMeasureOr404($id);

            if ($measure->deleted_at != null) {
                return redirect()->back()->with('info', "A medida, <strong>'$measure->name'</strong> encontra-se excluída. Portanto não é possivel exclui-la");
            }

            // Preparar os dados e enviar para o database
            $measure->fill($this->request->getPost());

            // Não houve alteração nos dados, não atualiza o database
            if (!$measure->hasChanged()) {
                return redirect()->back()->with('warning', 'Não há dados para atualizar!');
            }

            if ($this->measureModel->save($measure)) {
                return redirect()->to(site_url("adm/measurements/show/$measure->id"))
                                ->with('success', "Medida <strong>'$measure->name'</strong>, atualizada com sucesso");
            } else {
                return redirect()->back()->with('errors_model', $this->measureModel->errors())
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
     * Pesquisa Medidas (measure) no banco de dados.
     *
     * @param int $id
     *
     * @return object $measure
     */
    private function findMeasureOr404(int $id = null)
    {
        // ->withDeleted(true) Pesquisar os measure deletados
        if (!$id || !$measure = $this->measureModel->withDeleted(true)->where('id', $id)->first()) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::ForPageNotFound("Não encontramos a medida $id");
        }

        return $measure;
    }

    /**
     * Excluir a medida no database (softdelete).
     *
     * @param int $id
     *
     * @return object $measure
     */
    public function excluir($id = null)
    {
        $measure = $this->findMeasureOr404($id);

        if ($measure->deleted_at != null) {
            return redirect()->back()->with('info', "A medida, <strong>'$measure->name'</strong> já encontra-se excluída.");
        }

        if ($this->request->getMethod() === 'post') {
            $this->measureModel->delete($id);

            return redirect()->to(site_url('adm/measurements'))
                             ->with('success', "Medida $measure->name, excluído com sucesso");
        }

        $data = [
         'title' => "Excluindo a medida: $measure->name",
         'measure' => $measure,
        ];

        return view('adm/Measurements/excluir', $data);
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
        $measure = $this->findMeasureOr404($id);

        if ($measure->deleted_at == null) {
            return redirect()->back()->with('info', 'Apenas medidas excluídos podem ser recuperadas!');
        }

        if ($this->measureModel->undoDelete($id)) {
            return redirect()->back()->with('success', 'Exclusão desfeita com sucesso.');
        } else {
            return redirect()->back()
                             ->with('errors_model', $this->measureModel->errors())
                             ->with('info', 'Por favor verifique os erros abaixo')
                             ->withInput();
        }
    }
}
