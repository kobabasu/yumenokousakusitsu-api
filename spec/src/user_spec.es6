import frisby from 'frisby'

const HOST   = 'http://localhost:8080/api/'
const MODEL = 'users/'

/* GET '/users/' */
frisby.create(
    "正常系 '/users/'で正しくJSONを返すか"
  )
  .get(HOST + MODEL)
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
      path: '20160215_012544',
      posted: '2016-02-15 01:25:44'
    },
    {
      id: '2',
      name: 'うさ子',
      approved: '0',
      path: '20160215_012544',
      posted: '2016-02-15 01:25:44'
    }
  ])
  .toss();

/* GET '/users/' */
frisby.create(
    "異常系 '/users/'でBASIC認証なしで401を返すか"
  )
  .get(HOST + MODEL)
  .expectHeader(
    'Content-Type',
    'application/json;charset=utf-8'
  )
  .toss();
