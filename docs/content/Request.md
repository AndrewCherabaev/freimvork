# Request 

Request is simple class for now contains only:
- full URI from `$_SERVER["REQUEST_URI"]`
- full path from `$_SERVER["PATH_INFO"]`
- request method from `$_SERVER["REQUEST_METHOD"]`
- queryParams from `$_SERVER["QUERY_STRING"]`
- and params as a combination of `$_REQUEST`, `$_GET` and `$_POST` global variables

Via magic we can retrieve any element from `$params` and the full path for Router matching