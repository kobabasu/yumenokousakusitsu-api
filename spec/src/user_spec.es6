import frisby from 'frisby'

const HOST   = 'http://localhost:8080/api/'
const MODEL = 'users/'

/* GET '/users/' */
frisby.create(
    "正常系 '/users/'で正しくJSONを返すか"
  )
  .get(HOST + MODEL)
  .auth('api', 'api012')
  .expectStatus(200)
  .expectHeader(
    'Content-Type',
    'application/json;charset=utf-8'
  )
  .expectJSON([
    {
      id: '1',
      name: 'ニックちゃん',
      approved: '1',
      path: '20160215-012544.png',
      posted: '2016-02-15 01:25:44'
    },
    {
      id: '2',
      name: 'うさ子',
      approved: '0',
      path: '20160215-012544.png',
      posted: '2016-02-15 01:25:44'
    }
  ])
  .toss();

/* GET '/users/pages/' */
frisby.create(
    "正常系 '/users/pages/'で正しくJSONを返すか"
  )
  .get(HOST + MODEL + 'pages/')
  .auth('api', 'api012')
  .expectStatus(200)
  .expectHeader(
    'Content-Type',
    'application/json;charset=utf-8'
  )
  .expectJSON([
    {
      id: '1',
      name: 'ニックちゃん',
      approved: '1',
      path: '20160215-012544.png',
      posted: '2016-02-15 01:25:44'
    }
  ])
  .toss();

/* GET '/users/pages/0' */
frisby.create(
    "正常系 '/users/pages/0'で1ページ目を返すか"
  )
  .get(HOST + MODEL + 'pages/0')
  .auth('api', 'api012')
  .expectStatus(200)
  .expectHeader(
    'Content-Type',
    'application/json;charset=utf-8'
  )
  .expectJSON([
    {
      id: '1',
      name: 'ニックちゃん',
      approved: '1',
      path: '20160215-012544.png',
      posted: '2016-02-15 01:25:44'
    }
  ])
  .toss();

/* GET '/users/pages/1' */
frisby.create(
    "正常系 '/users/pages/1'で1ページ目を返すか"
  )
  .get(HOST + MODEL + 'pages/1')
  .auth('api', 'api012')
  .expectStatus(200)
  .expectHeader(
    'Content-Type',
    'application/json;charset=utf-8'
  )
  .expectJSON([
    {
      id: '1',
      name: 'ニックちゃん',
      approved: '1',
      path: '20160215-012544.png',
      posted: '2016-02-15 01:25:44'
    }
  ])
  .toss();

/* GET '/users/pages/2' */
frisby.create(
    "正常系 '/users/pages/2'で空を返すか"
  )
  .get(HOST + MODEL + 'pages/2')
  .auth('api', 'api012')
  .expectStatus(200)
  .expectHeader(
    'Content-Type',
    'application/json;charset=utf-8'
  )
  .expectJSON([])
  .toss();

/* GET '/users/pages/taro' */
frisby.create(
    "異常系 '/users/pages/taro'引数が数値以外で404を返すか"
  )
  .get(HOST + MODEL + 'pages/taro')
  .auth('api', 'api012')
  .expectStatus(404)
  .toss();

/* GET '/users/' */
frisby.create(
    "異常系 '/users/'でBASIC認証なしで401を返すか"
  )
  .get(HOST + MODEL)
  .auth('api', 'api0123')
  .expectStatus(401)
  .expectHeader(
    'Content-Type',
    'application/json;charset=utf-8'
  )
  .toss();
