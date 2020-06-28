<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Utility\Security;
use Firebase\JWT\JWT;

/**
 * Users Controller
 *
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{

    /**
   * Initialize
   *
   * @author Md Fahmid
   * @method
   * @param
   * @header
   * @return
   * @redirect
   * @throws
   * @access
   * @static
   * @since 22 Oct 2019
   */
  public function initialize()
  {
    parent::initialize();
    $this->loadComponent('Auth');
    $this->loadComponent('RequestHandler');
    $this->Auth->allow([
      'login','add'
    ]);
  }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $users = $this->Users;
        $response = [
            'success' => true,
            'message' => __d('oauth', 'user_list_info'),
            'users' => $users
          ];
       $this->set($response);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);

        $this->set('user', $user);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['controller' => 'Users', 'action' => 'login']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function login()
    {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                $oauthTokenAccessTableReg = TableRegistry::get('OauthTokenAccess');
                $key = Security::salt();
                $expired = time() + (60 * 5);
                $refreshExprired = time() + (60 * 20); 
                $token =  JWT::encode([
                    'alg' => 'HS256',
                    'id' => $user['id'],
                    'sub' => $user['id'],
                    'iat' => time(),
                    'exp' => $expired, 
                ],
                $key);
                $refreshToken =  JWT::encode([
                    'alg' => 'HS256',
                    'id' => $user['id'],
                    'sub' => $user['id'],
                    'iat' => time(),
                    'exp' => $refreshExprired,
                ],
                $key);
                $oauthTokenData = $oauthTokenAccessTableReg->newEntity();
                $oauthTokenData->users_id = $user['id'];
                $oauthTokenData->token = $token;
                $oauthTokenData->expired = $expired;
                $oauthTokenData->created = strtotime(date('Y-m-d H:i'));
                $oauthTokenData->refresh_token = $refreshToken;
                $oauthTokenAccessTableReg->save($oauthTokenData);
               
                $response = [            
                    'success' => true,
                    'message' => __d('oauth', 'login_success'),
                    'expired' => $expired,
                    'refreshExprired' => $refreshExprired,
                    'data' => [ 
                      'token' => $token,
                      'refresh_token' => $refreshToken
                    ],            
                    '_serialize' => ['success', 'data', 'user', 'key']
                ];
            
            $this->set($response);
            }

            $this->Flash->error(__('Invalid email or password, try again'));
        }
    }

    public function logout()
    {
        $session = $this->request->session();
        $session->destroy();
        return $this->redirect($this->Auth->logout());
    }
}
