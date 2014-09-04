# OAuth 2.0 Provider Application Example

## About

This example provides a very simplistic implementation of the OAuth Provider component
bundled with lumina.

Although fully functional, this example doesn't implement any permission prompting
or scopes support. It is to be used on an environment where the clients are trusted
to access the information.

If you want a more complete example of the usage of this component, you can check
the `oauth-provider-scopes` example.

## Installing

Because of the nature of the component, a database is needed to store user information,
authorization codes and access tokens.

That being said, you can find the `structure.sql` and `population.sql` files to be
used with MySQL. Those will provide you with the database structure needed to run this
example and also create a default user that can be used to log in.

* default username: testuser
* default password: luminatest

Don't forget to check the `application/settings/default.php` file to fill out your
database configuration details.

## Implementation

This component was designed with costumization in mind and therefore you can provide 
your own classes for almost anything that is used by it.

### Data objects

You can create your own data classes by implementing the IAccessToken and IAuthCode.

To make use of those you can create your own authorization server (IAuthorizationServer) 
or extend the existing one and override the build* methods.

### Role objects

There are three basic roles on our implementation: the authorization server, the
client and the resource owner (IAuthorizationServer, IClient and IResourceOwner).

Both the authorization server and the client classes are provided, however, you must
implement the IResourceOwner on your own User class.

### Flow objects

The flow objects control what happens on each endpoint (authorization and token).

We provide all standard flows already, but you can override them with your custom
ones (by defining your new classes on the component's configuration) or even create
completly new flows.

The flow objects will take any necessary information from the Request object,
validate it if necessary, and then interact with the authorization server to accomplish
their task.

### Exceptions

We provide two types of exception, one for each endpoint (OAuthAuthorizationException 
and OAuthTokenGrantException).
Both have a set of predefined error codes, but custom ones can be provided.

These exceptions are already properly handled by the component and result in proper
error reporting or redirection, as stated on the OAuth 2.0 specification.
