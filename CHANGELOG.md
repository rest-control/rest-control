#### v0.6.0-alpha
* [\#32 [ExpressionLanguage] regex helper](https://github.com/rest-control/rest-control/issues/32)
* Change moreThan helper name into greaterThan

#### v0.5.0-alpha
* Add jsonSerializable in request/response chain, statsCollector, tcDelegate, tcSuiteObject and tsObject
* [\#5 [RequestHelper] OAuth 2](https://github.com/rest-control/rest-control/issues/15)
* [\#20 JSON test result output](https://github.com/rest-control/rest-control/issues/20)
* [\#21 HTML test results output](https://github.com/rest-control/rest-control/issues/21)
* [\#26 ContentType response filter](https://github.com/rest-control/rest-control/issues/26)
* [\#28 [ExpressionLanguage] BeforeDate filter](https://github.com/rest-control/rest-control/issues/28)
* [\#29 [ExpressionLanguage] afterDate filter](https://github.com/rest-control/rest-control/issues/29)
* Update RunTests command(add report & report-dir)
* Move pipeline creating process
* [\#30 [ResponseFilter] Custom filter](https://github.com/rest-control/rest-control/issues/30)

#### v0.4.0-alpha
* [\#3 [ExpressionLanguage] lessThan helper](https://github.com/rest-control/rest-control/issues/3)
* "EachItems" expression language helper
* [\#4 [ExpressionLanguage] greaterThan helper](https://github.com/rest-control/rest-control/issues/4)
* Add global helpers functions
* Add before and after tests suite events
* [\#18 Before and after testCases group](https://github.com/rest-control/rest-control/issues/18)
* [\#16 [RequestHelper] HTTP basic authentication](https://github.com/rest-control/rest-control/issues/16)
* [\#14 [RequestHelper] helper connect](https://github.com/rest-control/rest-control/issues/14)
* [\#13 [RequestHelper] helper trace](https://github.com/rest-control/rest-control/issues/13)
* [\#12 [RequestHelper] helper options](https://github.com/rest-control/rest-control/issues/12)
* [\#11 [RequestHelper] helper purge](https://github.com/rest-control/rest-control/issues/11)
* [\#10 [RequestHelper] helper delete](https://github.com/rest-control/rest-control/issues/10)
* [\#9 [RequestHelper] helper patch](https://github.com/rest-control/rest-control/issues/9)
* [\#8 [RequestHelper] helper put](https://github.com/rest-control/rest-control/issues/8)
* [\#7 [RequestHelper] helper head](https://github.com/rest-control/rest-control/issues/7)
* [\#5 [ResponseFilters] httpCode helper](https://github.com/rest-control/rest-control/issues/5)

#### v0.3.1-alpha
* Create ISSUE_TEMPLATE
* Create PULL_REQUEST_TEMPLATE
* Create CHANGELOG
* Create CONTRIBUTING
* [Feature #6 Traits with helpers](https://github.com/rest-control/rest-control/issues/6) 

#### v0.3.0-alpha
*  Posibility to validate list of items in response filters
*  Implementation of jsonPath in jsonPath helper
*  Implementation of jsonPath in hasItem and hasItems filters
*  Fix test tags
*  Mock api client
*  Mockable server responses
*  Array validator adapter

#### v0.2.0-alpha
 *  Simple statistic collector for TestCase
 *  HasItem response filter and response items definition
 *  Implementations of validation adapters(date, email, float, hostname, iban, int, ip, isbn, length, notEmpty, numeric, regex, string, uri, uuid)
 *  New layout of run tests command ouput
 *  Fixes for CI scripts
 *  Add statistics in run tests command summary
 *  More examples with different response filters
 *  Remove multidirections in tests loader
 *  Use default namespace in create test command
 *  Add optional jsonPath in hasItem response filter
 *  Fix problem with autoload custom response filters

#### v0.1.0-alpha
 *  Base structure for RestControl application
 *  RestControl-Console application
   *  Run all tests
   *  Creaing TestCase from command line
 *  Set of basic response filters(header, json, jsonPath)
 *  Set of basic expression language(endsWith, equalsTo, startsWith)
 *  PSR class loader for TestCases
 *  HTTP Api client for sending rest requests