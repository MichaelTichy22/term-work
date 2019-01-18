<?php

class PostController extends Controller
{
    /**
     * @param $parameters
     */
    public function showAction($parameters)
    {
        $this->checkParametersMaxCount($parameters, 3);

        $postManager = new PostManager();
        $tagManager = new TagManager();

        $postId = $parameters[0];
        $post = $postManager->getById($this->db,$postId);

        if(!empty($parameters[1])){
            if(isset($_SESSION['user'])){
                if($parameters[1] !== 'edit-comment'){
                    $this->redirect('error/er404');
                }
            }
        }

        $this->head = [
            'title' => $post['title'],
            'keywords' => 'přispěvek, blog, článek',
            'description' => 'Detaily '.$post['title'],
        ];

        $this->data['id'] = $postId;
        $this->data['title'] = $post['title'];
        $this->data['content'] = $post['content'];
        $this->data['createDate'] = $post['create_date'];
        $this->data['tags'] = $tagManager->getAllByPostId($this->db, $postId);

        $this->view = 'Post/show';
    }

    /**
     * @param $parameters
     */
    public function createAction($parameters)
    {
        $this->checkParametersMaxCount($parameters, 0);

        if(isset($_SESSION['user'])){
            if($_SESSION['user']['role']==0){
                $this->redirect('home/index');
            }
            $postManager = new PostManager();
            $form = new PostForm('postForm','post','/post/create');
            $form->build($this->db);
            $form->addElement('submit-create', '', 'input',[
                'type' => 'submit',
                'class' => 'btn-blue',
            ], 'Vytvořit');

            $this->data['messages'] = [];

            if($_SERVER['REQUEST_METHOD']=='POST'){
                $messages = [];
                $form->setValues([$_POST['title'], $_POST['content'], 'checkboxes' => $_POST['tags']]);
                if($form->isValid()){
                    $postManager->createPost(
                        $this->db,
                        [
                            htmlspecialchars($_POST['title']),
                            nl2br(htmlspecialchars($_POST['content'])),
                            $_SESSION['user']['id_user'],
                        ],
                        $_POST['tags']
                    );

                    $messages = ['Příspěvek byl úspěšně vytvořen'];
                }else{
                    $messages = $form->getMessages();
                }
                if (isset($_POST['ajax'])){
                    foreach ($messages as $message){
                        echo nl2br($message."\n");
                    }
                    exit;
                }else{
                    $_SESSION['message'] = $messages;
                    $this->redirect('post/create',true, 303);
                }
            }

            $this->head = [
                'title' => 'Vytvořit příspěvěk',
                'keywords' => 'přispěvek, vytvořit, formulář',
                'description' => 'Formulář pro vytvoření příspěvku ',
            ];

            $this->data['form'] = $form;
            $this->data['header'] = 'Vytvořit příspěvěk';

            $this->view = 'Post/form';

        }else{
            $this->redirect('home/index');
        }
    }

    /**
     * @param $parameters
     */
    public function editAction($parameters)
    {
        $this->checkParametersMaxCount($parameters, 1);

        if(isset($_SESSION['user'])) {
            if ($_SESSION['user']['role'] != 2) {
                $this->redirect('home/index');
            }

            $postManager = new PostManager();
            $postId = $parameters[0];
            $post = $postManager->getById($this->db, $postId);
            if (!$post) {
                $this->redirect('error/er404');
            }

            $form = new PostForm('postForm', 'post', '/post/edit/' . $postId);
            $form->build($this->db);
            $form->addElement('submit-edit', '', 'input', [
                'type' => 'submit',
                'class' => 'btn-blue',
            ], 'Editovat');

            $tagManager = new tagManager();
            $tags = $tagManager->getAllByPostId($this->db, $postId);
            $tagIds = [];

            foreach ($tags as $tag) {
                $tagIds[] = $tag['id_tag'];
            }

            $form->setValues([
                $post['title'],
                str_replace('<br />', "", $post['content']),
                'checkboxes' => $tagIds,
            ]);

            $this->data['messages'] = [];

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $form->setValues([$_POST['title'], $_POST['content'], 'checkboxes' => $_POST['tags']]);
                $messages = [];

                if ($form->isValid()) {
                    $postManager->editPost(
                        $this->db,
                        [
                            htmlspecialchars($_POST['title']),
                            nl2br(htmlspecialchars($_POST['content'])),
                            $postId,
                        ],
                        $_POST['tags']
                    );
                    $messages = ['Příspěvek byl úspěšně editován'];
                } else {
                    $messages = $form->getMessages();
                }

                $_SESSION['message'] = $messages;
                $this->redirect('post/edit/' . $postId, true, 303);
            }

            $this->head = [
                'title' => 'Editovat příspěvěk',
                'keywords' => 'přispěvek, editovat, formulář',
                'description' => 'Formulář pro editaci příspěvku ',
            ];

            $this->data['form'] = $form;
            $this->data['header'] = 'Editovat příspěvěk';

            $this->view = 'Post/form';

        }else {
            $this->redirect('home/index');
        }
    }

    /**
     * @param $parameters
     */
    public function deleteAction($parameters)
    {
        if(isset($_SESSION['user'])){
            if($_SESSION['user']['role']==0){
                $this->redirect('home/index');
            }
            $this->checkParametersMaxCount($parameters, 1);

            if (empty($parameters[0])){
                $this->redirect('error/er404');
            }

            $postManager = new PostManager();

            $postManager->deleteById($this->db, $parameters[0]);

            $this->redirect('home/index');
        }else{
            $this->redirect('home/index');
        }
    }


}