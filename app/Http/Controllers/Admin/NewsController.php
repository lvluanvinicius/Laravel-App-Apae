<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\NewsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\NewsRequest;
use App\Http\Requests\NewsUpdateRequest;
use App\Models\Category;
use App\Models\News;
use Exception;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    /**
     * Guarda o modelo de Noticias/Posts.
     *
     * @var News
     */
    protected News $news;

    /**
     * Construtor de inicialização.
     */
    public function __construct()
    {
        $this->news = new News();
    }

    /**
     * Retorna o display de listagens de notícias.
     *
     * @author Luan Santos <lvluansantos@gmail.com>
     * @return View
     */
    public function index(): View
    {
        try {
            // Recuperando notícias.
            $news = $this->news->get();

            return view("pages.admin.news.index")->with([
                'title'             => 'Notícias',
                'news'              => $news,
            ]);
        } catch (NewsException $error) {
            return redirect()->back()->with([
                'status' => false,
                'message' => $error->getMessage(),
                'type' => 'Error',
            ])->withInput();
        } catch (Exception $error) {
            return redirect()->back()->with([
                'status' => false,
                'message' => $error->getMessage(),
                'type' => 'Error',
            ])->withInput();
        }
    }

    /**
     * Exibe formulario de criação de post.
     *
     * @author Luan Santos <lvluansantos@gmail.com>
     * @return View|RedirectResponse
     */
    public function create(): View|RedirectResponse
    {
        try {
            $categories = Category::get();

            return view("pages.admin.news.create")->with([
                'title'             => 'Nova Publicação',
                'categories'        => $categories,
            ]);
        } catch (NewsException $error) {
            return redirect()->back()->with([
                'status' => false,
                'message' => $error->getMessage(),
                'type' => 'Error',
            ])->withInput();
        } catch (Exception $error) {
            return redirect()->back()->with([
                'status' => false,
                'message' => $error->getMessage(),
                'type' => 'Error',
            ])->withInput();
        }
    }

    /**
     * Cria um novo post.
     *
     * @param NewsRequest $request
     * 
     * @author Luan Santos <lvluansantos@gmail.com>
     * @return RedirectResponse
     */
    public function store(NewsRequest $request): RedirectResponse
    {
        try {
            // Recuperando categoria.
            $category = Category::where('id', $request->cod_category_fk)->first();

            // Valida se a categoria existe.
            if (!$category) throw new NewsException('Categoria informada não existe.');

            // Recuperando dados da requisição.
            $requestData = $request->only([
                "news_post_title",
                "cod_category_fk",
                "news_post_content",
                "news_post_slug",
                "news_post_summary",
                "news_post_tags"
            ]);

            dd('teste');

            // Inserindo ID de usuário.
            $requestData['cod_user_fk'] = auth()->user()->id;

            // Validando HTML.
            $config = HTMLPurifier_Config::createDefault();
            $purifier = new HTMLPurifier($config);
            $requestData['news_post_content'] = $purifier->purify($requestData['news_post_content']);

            // Criando POST.
            $post = $this->news->create($requestData);

            // Validando criação.
            if ($post) {
                return redirect()->route('admin.news.index')->with([
                    'status'        => true,
                    'message'       => "Post criado com sucesso.",
                    'type'          => 'Success',
                ]);
            }

            throw new NewsException('Post não pode ser criado. Por favor, tente novamente.');
        } catch (NewsException $error) {
            return redirect()->back()->with([
                'status' => false,
                'message' => $error->getMessage(),
                'type' => 'Error',
            ])->withInput();
        } catch (Exception $error) {
            return redirect()->back()->with([
                'status' => false,
                'message' => $error->getMessage(),
                'type' => 'Error',
            ])->withInput();
        }
    }

    /**
     * Exibe o display de edição.
     *
     * @param string $newsId
     * 
     * @author Luan Santos <lvluansantos@gmail.com>
     * @return View|RedirectResponse
     */
    public function edit(string $newsId): View|RedirectResponse
    {
        try {
            // Recuperando notícia. 
            $news = $this->news->where('id', $newsId)->first();

            // Valida se existe.
            if (!$news) throw new NewsException('Notícia não encontrada para edição.');

            // Recupera todas as categorias.
            $categories = Category::get();

            return view("pages.admin.news.edit")->with([
                'title'             => 'Nova Publicação',
                'categories'        => $categories,
                'news'              => $news,
            ]);
        } catch (NewsException $error) {
            return redirect()->back()->with([
                'status' => false,
                'message' => $error->getMessage(),
                'type' => 'Error',
            ])->withInput();
        } catch (Exception $error) {
            return redirect()->back()->with([
                'status' => false,
                'message' => $error->getMessage(),
                'type' => 'Error',
            ])->withInput();
        }
    }

    public function update(NewsUpdateRequest $request, string $newsId): RedirectResponse
    {

        try {
            // Recuperando categoria.
            $category = Category::where('id', $request->cod_category_fk)->first();
            // Valida se a categoria existe.
            if (!$category) throw new NewsException('Categoria informada não existe.');

            // Recuperando notícia. 
            $news = $this->news->where('id', $newsId)->first();
            // Valida se existe.
            if (!$news) throw new NewsException('Notícia não encontrada para edição.');

            // Recuperando dados da requisição.
            $requestData = $request->only([
                "news_post_title",
                "cod_category_fk",
                "news_post_content",
                "news_post_slug",
                "news_post_summary",
                "news_post_tags"
            ]);

            // Validando slug
            $newsslug = $this->news->where('news_post_slug', $requestData['news_post_slug'])->where('id', '!=', $newsId)->first();
            // Valida se existe.
            if (!$newsslug) throw new NewsException('Esse slug já está em uso em outra notícia.');

            // Inserindo ID de usuário.
            $requestData['cod_user_fk'] = auth()->user()->id;

            // Validando HTML.
            $config = HTMLPurifier_Config::createDefault();
            $purifier = new HTMLPurifier($config);
            $requestData['news_post_content'] = $purifier->purify($requestData['news_post_content']);

            // Criando POST.
            $post = $this->news->where('id', $newsId)->update($requestData);

            // Validando criação.
            if ($post) {
                return redirect()->route('admin.news.index')->with([
                    'status'        => true,
                    'message'       => "Post atualizado com sucesso.",
                    'type'          => 'Success',
                ]);
            }

            throw new NewsException('Post não pode ser criado. Por favor, tente novamente.');
        } catch (NewsException $error) {
            return redirect()->back()->with([
                'status' => false,
                'message' => $error->getMessage(),
                'type' => 'Error',
            ])->withInput();
        } catch (Exception $error) {
            return redirect()->back()->with([
                'status' => false,
                'message' => $error->getMessage(),
                'type' => 'Error',
            ])->withInput();
        }
    }
}
