import frisby from 'frisby'

const HOST   = 'http://localhost:8080/api/'
const MODEL = 'pages/'

/* GET '/pages/2' */
frisby.create(
    "正常系 '/pages/2'で空を返すか"
  )
  .get(HOST + MODEL + '2')
  .auth('api', 'api012')
  .expectStatus(200)
  .expectHeader(
    'Content-Type',
    'application/json;charset=utf-8'
  )
  .toss();

/* GET '/pages/taro' */
frisby.create(
    "異常系 '/pages/taro'引数が数値以外で404を返すか"
  )
  .get(HOST + MODEL + 'taro')
  .auth('api', 'api012')
  .expectStatus(404)
  .toss();
