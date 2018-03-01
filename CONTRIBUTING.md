#Contributing
Thank you for considering contributing to RestControl! If you'd like to contribute, please read the following standards and practices.
Please direct all questions or concerns to main contributor's email address: kamil.szela@cothe.pl. 

##Versioning
This package is versioned under the [Semantic Versioning](http://semver.org/) guidelines as much as possible.

Releases will be numbered using following format:

`<major>.<minor>.<patch>`

##Coding standards
Code in this repository adheres to: [PSR-1](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md), [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) and [PSR-4](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md).

To resolve any issues found in this project please create pull request for your fix/patch.

It is highly advisable to use PHP CS Fixer 2.10.0 on your changes as this will help to ensure that coding standard is consistent throughout the project.

All new functionalities should be covered by unit tests. In case of amendments on existing functionality all related unit tests should be adjusted so that no failures occur.


##Pull requests
When creating pull request, please select appropriate label depending on the nature of changes made.

####Proposal \ Feature 
Before starting to work on a feature please open and issue prefixed with "[Proposal]" lablel, to do so visit [Issues](https://github.com/rest-control/rest-control/issues). Provide detailed description for why the change is being done, based on this RestControl team will either approve or reject the issue.

As soon as issue is approved create new branch and start working, once done create pull request to master branch. Be sure to include issue id in the pull request title.

####Bugs
When submitting fix there is no requirement for opening an issue, just create pull request including detailed description of the bug/problem.

####Which branch ? 
When creating new branch please use most recent and stable RestControl version.

####Requirements
Automated build will be performed for each pull request, at which point all unit tests will be executed. 100% of the unit tests must be successful before changes from given pull request can be merged.

Please note that all pull requests that will fail to meet above mentioned requirements might be deleted at any time.