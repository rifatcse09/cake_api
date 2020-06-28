<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * List Controller
 *
 *
 * @method \App\Model\Entity\List[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ListController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        //$list = $this->paginate($this->List);
        $characters = [
            'Daenerys Targaryen' => 'Emilia Clarke',
            'Jon Snow'           => 'Kit Harington',
            'Arya Stark'         => 'Maisie Williams',
            'Melisandre'         => 'Carice van Houten',
            'Khal Drogo'         => 'Jason Momoa',
            'Tyrion Lannister'   => 'Peter Dinklage',
            'Ramsay Bolton'      => 'Iwan Rheon',
            'Petyr Baelish'      => 'Aidan Gillen',
            'Brienne of Tarth'   => 'Gwendoline Christie',
            'Lord Varys'         => 'Conleth Hill'
          ];
   
        $this->set(compact('characters'));
       // $this->set(compact('list'));
    }

    /**
     * View method
     *
     * @param string|null $id List id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $list = $this->List->get($id, [
            'contain' => [],
        ]);

        $this->set('list', $list);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $list = $this->List->newEntity();
        if ($this->request->is('post')) {
            $list = $this->List->patchEntity($list, $this->request->getData());
            if ($this->List->save($list)) {
                $this->Flash->success(__('The list has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The list could not be saved. Please, try again.'));
        }
        $this->set(compact('list'));
    }

    /**
     * Edit method
     *
     * @param string|null $id List id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $list = $this->List->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $list = $this->List->patchEntity($list, $this->request->getData());
            if ($this->List->save($list)) {
                $this->Flash->success(__('The list has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The list could not be saved. Please, try again.'));
        }
        $this->set(compact('list'));
    }

    /**
     * Delete method
     *
     * @param string|null $id List id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $list = $this->List->get($id);
        if ($this->List->delete($list)) {
            $this->Flash->success(__('The list has been deleted.'));
        } else {
            $this->Flash->error(__('The list could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
