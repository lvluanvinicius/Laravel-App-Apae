<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\PartnersException;
use App\Http\Controllers\Controller;
use App\Http\Requests\PartnersCreateRequest;
use App\Models\Partners;
use App\Models\Settings;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PartnersController extends Controller
{
    /**
     * Exibe a listagem de parceiros.
     *
     * @author Luan Santos <lvluansantos@gmail.com>
     * @return View
     */
    public function index(): View
    {
        // Recuperando parceiros.
        $partners = Partners::get();
        
        return view('pages.admin.partners.index')->with([
            'title'             => 'Parceiros',
            'partners'          => $partners
        ]);
    }

    /**
     * Exibe o display de detalhes do parceiro.
     *
     * @param string $partnerID
     * 
     * @author Luan Santos <lvluansantos@gmail.com>
     * @return View
     */
    public function show(string $partnerID): View
    {
        // Recuperando parceiro.
        $partner = Partners::where('id', $partnerID)->first();

        return view('pages.admin.partners.view-partners')->with([
            'title'             => $partner->partner_name,
            'partner'           => $partner,
        ]);
    }

    /**
     * Undocumented function
     *
     * @author Luan Santos <lvluansantos@gmail.com>
     * @return View
     */
    public function create(): View
    {
        return view('pages.admin.partners.create')->with([
            'title'         => 'Novo Parceiro'
        ]);
    }

    /**
     * Efetua a inserção de um novo parceiro.
     *
     * @param PartnersCreateRequest $request
     * 
     * @author Luan Santos <lvluansantos@gmail.com>
     * @return RedirectResponse
     */
    public function store(PartnersCreateRequest $request): RedirectResponse
    {
        try {
            // Verificando se o arquivo foi informado.
            if (!$request->hasFile('partner_image')) {
                throw new PartnersException('Informe uma imagem para o parceiro.');
            }

            // Validando se a imagem é válida.
            if (!$request->file('partner_image')->isValid()) {
                throw new PartnersException('Informe uma imagem válida para o parceiro.');
            }

            $file                   = $request->file('partner_image'); # Recupera o arquivo.
            $ext                    = $file->getClientOriginalExtension(); # Recupera a extensão da imagem.
            $fileName               = $file->getClientOriginalName(); # Recupera a extensão da imagem.
            $fileSize               = $file->getSize(); # Recupera o tamanho do arquivo.
            $fileType               = $file->getType(); # Recupera o tipo do arquivo.

            // Recuperando extensões aceitas.
            $settings               = Settings::where('setting_name', 'application_partners_extensions')->first('setting_value');

            // Transformando as extensões.
            $extensions             = unserialize($settings->setting_value);

            // Verificando extensões.
            $validateExtensions     = array_filter($extensions, function ($extV) use ($ext) {
                if ($extV == $ext) return true;
                return false;
            });

            // Validando extensão.
            if (!$validateExtensions) {
                throw new PartnersException("Verifique a extensão da imagem, são aceito apenas '" . join('|', $extensions) . "'");;
            }

            // Gerando hash para o arquivo. 
            $fileHashString         = "";
            $f                      = true;
            while ($f) {
                $s                  = md5($fileName . rand(100, 100000));
                $partFile           = Partners::where('partner_hash', $s)->first();
                if (!$partFile) {
                    $f              = false;
                    $fileHashString     = $s;
                }
            }

            // Inserindo parceiro na base de dados. 
            $partner                = Partners::create([
                "partner_name"              => $request->partner_name,
                "partner_image"             => $fileHashString . "." . $ext,
                "partner_image_size"        => $fileSize,
                "partner_image_type"        => $fileType,
                "partner_hash"              => $fileHashString,
            ]);

            // Verificando se o parceiro foi criado. 
            if ($partner) {
                // Recuperando path das imagens de parceiros.
                $imagePath          = Settings::where('setting_name', 'application_partners_path')->first('setting_value');

                // Efetuando upload da imagem.
                $file->move($imagePath->setting_value, "{$fileHashString}.{$ext}");
                
                return redirect()->route('admin.partners.index')->with([
                    'status'        => true,
                    'message'       => "Parceiro criado com sucesso.",
                    'type'          => 'Success',
                ]);
            }

            throw new PartnersException('Não foi possível salvar o parceiro.');
        } catch (PartnersException $error) {
            return redirect()->back()->with([
                'status'        => false,
                'message'       => $error->getMessage(),
                'type'          => 'Error',
            ])->withInput();
        } catch (Exception $error) {
            return redirect()->back()->with([
                'status'        => false,
                'message'       => $error->getMessage(),
                'type'          => 'Error',
            ])->withInput();
        }
    }
}
