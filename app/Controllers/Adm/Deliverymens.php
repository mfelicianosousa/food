<?php

namespace App\Controllers\Adm;

use App\Controllers\BaseController;
use App\Entities\Deliverymen;

class Deliverymens extends BaseController
{
    private $deliverymenModel;
    private $page = 10;

    public function __construct()
    {
        $this->deliverymenModel = new \App\Models\DeliverymenModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Listando os Entregadores',
            'deliverymens' => $this->deliverymenModel->withDeleted(true)->paginate($this->page),
            'pager' => $this->deliverymenModel->pager,
        ];

        return view('Adm/Deliverymens/index', $data);
    }

    public function show($id = null)
    {
        $deliverymen = $this->findDeliverymenOr404($id);

        // dd($deliverymen);

        $data = [
         'title' => "Detalhes do entregador: $deliverymen->name",
         'deliverymen' => $deliverymen,
        ];

        return view('adm/Deliverymens/show', $data);
    }

    public function edit($id = null)
    {
        $deliverymen = $this->findDeliverymenOr404($id);

        // dd($deliverymen);

        $data = [
         'title' => "Editar o entregador: $deliverymen->name",
         'deliverymen' => $deliverymen,
        ];

        return view('adm/Deliverymens/edit', $data);
    }

    public function update($id = null)
    {
        if ($this->request->getMethod() === 'post') {
            $deliverymen = $this->findDeliverymenOr404($id);

            $post = $this->request->getPost();
            $deliverymen->fill($post);

            // dd($this->request->getPost());
            // Não houve alteração nos dados, não atualiza o database

            if (!$deliverymen->hasChanged()) {
                return redirect()->back()->with('info', 'Não há dados para atualizar');
            }

            // Salvar na base de dados
            if ($this->deliverymenModel->save($deliverymen)) {
                return redirect()->to(site_url("adm/deliverymens/show/$deliverymen->id"))
                                    ->with('success', "Entregador <strong>'$deliverymen->name'</strong>, atualizado com sucesso!");
            } else {
                return redirect()->back()->with('errors_model', $this->deliverymenModel->errors())
                                        ->with('info', 'Por favor verifique os erros abaixo')
                                        ->withInput();
            }
        } else {
            return redirect()->back();
        }
    }

    public function search()
    {
        if (!$this->request->isAJAX()) {
            exit('Página não encontrada!');
        }
        $deliverymens = $this->deliverymenModel->search($this->request->getGet('term'));

        $return = []; // variavel de retorno

        foreach ($deliverymens as $deliverymen) {
            $data['id'] = $deliverymen->id;
            $data['value'] = $deliverymen->name;
            $return[] = $data;
        }

        return $this->response->setJSON($return);
    }

    public function create()
    {
        $deliverymen = new Deliverymen();

        // dd($deliverymen);

        $data = [
         'title' => 'Novo Entregador',
         'deliverymen' => $deliverymen,
        ];

        return view('adm/Deliverymens/create', $data);
    }

    public function register()
    {
        if ($this->request->getMethod() === 'post') {
            $deliverymen = new Deliverymen($this->request->getPost());

            // dd($deliverymen);
            // Não houve alteração nos dados, não atualiza o database

            // Salvar na base de dados
            if ($this->deliverymenModel->save($deliverymen)) {
                return redirect()->to(site_url('adm/deliverymens/show/'.$this->deliverymenModel->getInsertID()))
                                    ->with('success', "Entregador <strong>'$deliverymen->name'</strong>, cadastrado com sucesso!");
            } else {
                return redirect()->back()->with('errors_model', $this->deliverymenModel->errors())
                                        ->with('info', 'Por favor verifique os erros abaixo')
                                        ->withInput();
            }
        } else {
            return redirect()->back();
        }
    }

    /**
     * delete function
     * Deletar o registro do Entregador (Deliverymen).
     *
     * @param [type] $id
     *
     * @return void
     */
    public function delete($id = null)
    {
        $deliverymen = $this->findDeliverymenOr404($id);

        if ($this->request->getMethod() === 'post') {
            $this->deliverymenModel->delete($id);

            // deliverymen tem imagem
            if ($deliverymen->image) {
                $pathImage = WRITEPATH.'uploads/deliverymens/'.$deliverymen->image;
                // dd($pathImage);
                if (is_file($pathImage)) {
                    unlink($pathImage);
                }
            }
            // Atualiza o produto tornando a image null
            $deliverymen->image = null;
            $this->deliverymenModel->save($deliverymen);

            return redirect()->to(site_url('adm/deliverymens'))->with('success', 'Entregador excluido com sucesso');
        }

        $data = [
            'title' => "Excluindo o Entregador $deliverymen->name",
            'deliverymen' => $deliverymen,
        ];

        return view('Adm/deliverymens/delete', $data);
    }

    /**
     * undoDelete function.
     *
     * Desfazer a exclusão do deliverymen no database (softdelete)
     *
     * @param int $id
     *
     * @return object $user
     */
    public function undoDelete($id = null)
    {
        $deliverymen = $this->findDeliverymenOr404($id);

        if ($deliverymen->deleted_at == null) {
            return redirect()->back()->with('info', 'Apenas entregadores excluídos podem ser recuperados!');
        }

        if ($this->deliverymenModel->undoDelete($id)) {
            return redirect()->back()->with('success', 'Exclusão desfeita com sucesso.');
        } else {
            return redirect()->back()
                             ->with('errors_model', $this->deliverymenModel->errors())
                             ->with('info', 'Por favor verifique os erros abaixo')
                             ->withInput();
        }
    }

    /**
     * Pesquisa o Entregador no banco de dados.
     *
     * @return object $deliverymen
     */
    private function findDeliverymenOr404(int $id = null)
    {
        if (!$id || !$deliverymen = $this->deliverymenModel->withDeleted(true)->where('id', $id)->first()) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::ForPageNotFound("Não encontramos o entregador $id");
        }

        return $deliverymen;
    }

    public function editImage(int $id = null)
    {
        $deliverymen = $this->findDeliverymenOr404($id);

        if ($deliverymen->deleted_at != null) {
            return redirect()->back()->with('info', 'Não é possivel editar a imagem de um Entregador excluído');
        }

        $data = [
         'title' => "Editando imagem do Entregador: $deliverymen->name",
         'deliverymen' => $deliverymen,
        ];

        return view('adm/deliverymens/edit_image', $data);
    }

    public function upload(int $id = null)
    {
        $deliverymen = $this->findDeliverymenOr404($id);

        $image = $this->request->getFile('image_deliverymen');

        if (!$image->isValid()) {
            $codeError = $image->getError();

            if ($codeError == UPLOAD_ERR_NO_FILE) {
                return redirect()->back()->with('info', 'Nenhum arquivo foi selecionado');
            }
        }
        $sizeImage = $image->getSizeByUnit('mb');

        if ($sizeImage > 2) {
            return redirect()->back()->with('info', 'O arquivo selecionado é muito grande. Máximo permitido é: 2MB');
        }

        // dd($image);
        $typeImage = explode('/', $image->getMimeType());
        $allowedTypes = ['jpeg', 'jpg', 'png', 'webp'];

        if (!in_array($typeImage[1], $allowedTypes)) {
            return redirect()->back()->with('info', 'O arquivo não tem o formato permitido. Apenas '.implode(', ', $allowedTypes));
        }

        list($width, $height) = getimagesize($image->getPathname());

        if ($width < '400' || $height < '400') {
            return redirect()->back()->with('info', 'A imagem não pode ser menor que 400 x 400 pixels');
        }
        // -- A partir desse ponto fazemos o store da image --//
        /* fazendo o store da imagem */
        $pathImage = $image->store('deliverymens');
        $pathImage = WRITEPATH.'uploads/'.$pathImage;
        /* fazendo o fit( recsize)da imagem */
        service('image')->withFile($pathImage)
                        ->fit(400, 400, 'center')
                        ->save($pathImage);

        /** Recuperando a imagem antiga, para exclui-la */
        $oldImage = $deliverymen->image;

        /* Atribuindo a nova imagem */
        $deliverymen->image = $image->getname();
        /* Atualizando a image do deliverymen */
        $this->deliverymenModel->save($deliverymen);

        /** Definindo o caminho da imagem antiga, para ser excluida */
        $pathImage = WRITEPATH.'uploads/deliverymens/'.$oldImage;

        if (is_file($pathImage)) {
            unlink($pathImage);
        }

        return redirect()->to(site_url("adm/deliverymens/show/$deliverymen->id"))
                         ->with('success', 'Imagem alterada com sucesso');
    }

    // Apresenta a imagem na tela

    public function image(string $image = null)
    {
        // dd($image);

        if ($image) {
            $imagePath = WRITEPATH."uploads\deliverymens".DIRECTORY_SEPARATOR.$image;

            $imageInfo = new \finfo(FILEINFO_MIME);

            // echo '<pre>';
            // print_r($imagePath);
            // echo '</pre>';
            // exit;
            // $imageInfo = new Finfo(FILEINFO_MIME_TYPE);
            // $imageInfo = new finfo();
            // $imageInfo->set_flags(FILEINFO_MIME_TYPE);
            $imageType = $imageInfo->file($imagePath);
            header("Content-Type: $imageType");
            header('Content-Length: '.filesize($imagePath));
            readfile($imagePath);
            exit;
        }
    }
}
