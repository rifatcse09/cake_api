<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\Http\Exception\UnauthorizedException;
use Cake\ORM\TableRegistry;
/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    public function beforeFilter(Event $event)
    {
        $this->Auth->allow(['index','view']);
        $this->set('loggedIn', $this->Auth->user());
    }

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('Flash');
        $this->loadComponent('Auth', [
            'userModel' => 'Users',
            'authenticate' => [
                'Form' => [
                    'fields' => ['username' => 'email','password' => 'password'],
                ],
                'ADmad/JwtAuth.Jwt' => [
                    'userModel' =>'Users',
                    'fields' => [
                        'username' => 'id'
                    ],
                    'parameter' => 'token',

                    // Boolean indicating whether the "sub" claim of JWT payload
                    // should be used to query the Users model and get user info.
                    // If set to `false` JWT's payload is directly returned.
                    'queryDatasource' => true,
                ]
            ],
            'unauthorizedRedirect' => false,
            'checkAuthIn' => 'Controller.initialize',

            // If you don't have a login action in your application set
            // 'loginAction' to false to prevent getting a MissingRouteException.
            'loginAction' => false,
            'authError' => __d('oauth', 'invalid_access')
        ]);
        $baseUrl = Router::url('/', true);
        $action = $this->request->action;
        if($action !='login'){
            $authorizationHeader = $this->request->getHeaderLine('Authorization');
            $oauth_token = substr($authorizationHeader, 7);
            $oauthTokenAccessTableReg = TableRegistry::get('OauthTokenAccess');
            $params=[
                'token !=' => '',
            ];
            if(!empty($oauth_token)){
                $params['OR']['token'] = $oauth_token;
               // $params['OR']['refresh_token'] = $oauth_token;
            }
            $isAuthorizedUser = $oauthTokenAccessTableReg->userStatus($params);
            if(!empty($isAuthorizedUser)){
            if($isAuthorizedUser->disabled!=0){
                throw new UnauthorizedException((__d('oauth', 'you_are_logout')));
            }
          }
        }
        $this->set(compact('baseUrl'));
        /*
         * Enable the following component for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
    }

       /**
   * Render JSON response on RESTful architecture
   *
   */
  public function beforeRender(Event $event)
  {

      parent::beforeRender($event);

      $action  = $this->request->params['action'];
      
      if($action != 'changePassword' && $action != 'changeSuccess' && $action != 'iframe'){            
          $this->RequestHandler->renderAs($this, 'json');
      }
      
      $this->response->withType('application/json');
      $this->response = $this->response->cors($this->request)
           ->allowOrigin([env('INGRESS_URL',`*`)])
           ->allowMethods(['GET', 'POST'])
           ->allowHeaders(['Content-Type','X-CSRF-Token','Authorization'])
           ->maxAge(172800)
           ->build();
      $this->set('_serialize', true);
  }

    
}
