#### v0.4.0-alpha
*  [\#3 [ExpressionLanguage] lessThan helper](https://github.com/rest-control/rest-control/issues/3)
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