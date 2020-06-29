<?php
namespace App\Controller\Api\Users;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Utility\Security;
use Firebase\JWT\JWT;
use Oil\Exception;

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
        $users = $this->Users->find('all');
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
        try{
            $user = $this->Users->get($id, [
                'contain' => [],
            ]);
            $response = [
                'success' => true,
                'message' => __d('oauth', 'user_info'),
                'users' => $user
            ];
        } catch(\Exception $e){
            $response = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
        $this->set($response);   
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        try{
            if (!$this->request->is('post')) throw new Exception(__('invalid_request'), 'method_not_allowed');
            $user = $this->Users->patchEntity($user, $this->request->getData());
            $this->Users->save($user);
            
            $response = [
                'success' => true,
            ];
        } catch(\Exception $e){
            $response = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
        
       $this->set($response);
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

        try{
            if (!$this->request->is(['patch', 'post', 'put'])) throw new Exception(__('invalid_request'), 'method_not_allowed');
            $user = $this->Users->get($id, [
                'contain' => [],
            ]);
            $user = $this->Users->patchEntity($user, $this->request->getData());
            $this->Users->save($user);
            
            $response = [
                'success' => true,
            ];
        } catch(\Exception $e){
            $response = [
                'success' => false,
                'message' => $e->getMessage(),
              ];
        }

        $this->set($response);
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

        try{
            if (!$this->request->is(['patch', 'post', 'put'])) throw new Exception(__('invalid_request'), 'method_not_allowed');
            $user = $this->Users->get($id);           
            $this->Users->delete($user);
            
            $response = [
                'success' => true,
                'message' => 'Deleted Successfully',
            ];
        } catch(\Exception $e){
            $response = [
                'success' => false,
                'message' => $e->getMessage(),
              ];
        }

        $this->set($response);
    }
  /**
   * Login
   *
   * @author Rifat rifatcse09@gmail.com
   * @method POST
   * @param string|null $email
   * @param string|null $password
   * @header
   * @return
   * @redirect
   * @throws
   * @access
   * @static
   * @since 29/06/2020
   */
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

   /**
   * Logout
   *
   * @author Rifat rifatcse09@gmail.com
   * @method GET
   * @header
   * @return
   * @redirect
   * @throws
   * @access
   * @static
   * @since 29/06/2020
   */
  public function logout()
  {
      try{
        $authorizationHeader = $this->request->getHeaderLine('Authorization');
        $oauth_token = substr($authorizationHeader, 7);    
        $oauthTokenAccessTableReg = TableRegistry::get('OauthTokenAccess');
        $params=[
             'token !=' => '',
          ];
        if(!empty($oauth_token)){
            $params['OR']['token'] = $oauth_token;
            $params['OR']['refresh_token'] = $oauth_token;
        }  
        $isAuthorizedUser = $oauthTokenAccessTableReg->userStatus($params);
        if(!empty($isAuthorizedUser)){        
          $isAuthorizedUser->disabled=1;
          $oauthTokenAccessTableReg->save($isAuthorizedUser); 
          $response = [            
                'success' => true,
                'message' => __d('oauth', 'logout_successfully'),
            ];   
       
        }else{
    
          throw new InternalErrorException('not_logout_successfully', 'bad_request');
        }
      } catch(\Exception $e){
        $response = [
            'success' => false,
            'message' => $e->getMessage(),
        ];
    }
   
    $this->set($response); 
  }
}
