<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Response;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Http\Resources\ContactResource;
use App\Jobs\UpdateContactScore;

class ContactController extends Controller
{
    /**
     * Exibe uma lista de contatos(findAll).
     */
    public function index()
    {
        return ContactResource::collection(Contact::paginate());
    }

    /**
     * criar um novo contato(create).
     */
    public function store(StoreContactRequest $request)
    {
        //criar um novo contato
        $contact = Contact::create($request->validated());

        return response(new ContactResource($contact), Response::HTTP_CREATED);
    }

    /**
     * Exibir contato pelo id(findOne).
     */
    public function show(Contact $contact)
    {
        return new ContactResource($contact);
    }

    /**
     * Atualizar contato(update).
     */
    public function update(UpdateContactRequest $request, Contact $contact)
    {
        $contact->update($request->validated());
        
        return new ContactResource($contact);
    }

    /**
     * remover contato(delete).
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function processScore(Contact $contact)
    {
        dispatch(new UpdateContactScore($contact));

        return response()->json([
            'message' => 'Score atualizado com sucesso'
        ]);
    }
}
