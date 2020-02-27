<?php


class DashboardController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->loadModels(['posts', 'categories', 'tags']);
    }

    public function index_action()
    {
        $this->view->posts = $this->postsModel->all([
            'values' => ['id', 'title', 'slug', 'label', 'category_id',
                        'created_at', 'updated_at', 'deleted'],
            'order' => 'id DESC',
        ], true);

        if (count($this->view->posts))
        {
            foreach ($this->view->posts as $post)
            {
                $post->prepareData();
            }
        }

        $this->view->render('dashboard/index');
    }
}