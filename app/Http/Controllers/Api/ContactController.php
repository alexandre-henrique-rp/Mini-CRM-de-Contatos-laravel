<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;


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
    public function store(StoreContactRequest $request)
    {
        //criar um novo contato
        $contact = Contact::create($request->validated());

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
    public function update(UpdateContactRequest $request, Contact $contact)
    {
        $contact->update($request->validated());
        
        return $contact;
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
