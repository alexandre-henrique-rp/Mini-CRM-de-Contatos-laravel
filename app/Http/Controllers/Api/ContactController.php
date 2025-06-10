<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Exibe uma lista de contatos(findAll).
     */
    public function index()
    {
        return Contact::paginate();
    }

    /**
     * criar um novo contato(create).
     */
    public function store(Request $request)
    {
        //validação dos dados do formulário
        $validadtedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:contacts,email',
            'phone' => 'required|string|max:20',
        ]);

        //criar um novo contato
        $contact = Contact::create($validadtedData);

        return response($contact, Response::HTTP_CREATED);
    }

    /**
     * Exibir contato pelo id(findOne).
     */
    public function show(string $id)
    {
        return Contact::find($id);
    }

    /**
     * Atualizar contato(update).
     */
    public function update(Request $request, string $id)
    {
        $validadtedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:contacts,email,' . $id,
            'phone' => 'sometimes|required|string|max:20',
        ]);

        $contact = Contact::findOrFail($id); // Garante que o contato existe
        $contact->update($validadtedData);
        $contact->refresh();
        return response($contact, Response::HTTP_OK);
    }

    /**
     * remover contato(delete).
     */
    public function destroy(string $id)
    {
        Contact::destroy($id);
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
