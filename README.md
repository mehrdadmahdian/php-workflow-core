<h2 align="center">PHP Workflow Engine For Simple Business Workflows</h2>
<h3 align="center">(Under Development - Do Not Use This)</h3>

<p align="center"> This is an amazing workflow engine to interact with your simple business workflows.</p>
<p align="center">This core build your business workflow model using simple activity array of configuration. Code client can run different actions on built process model and update model status.</p>

# Process Main Concepts

![alt Simple Process Example](spe.jpg)

It is assumed that each process is made up of multiple activity blocks which are connected to each other using transition flows.

- Each activity has its own unique name. This is the key to reach activity element.
- Each activity could be connected to multiple sources. Sources must be in type of activity.
    - An activity could have no source. It means on start action, this activity's status will be updated to `active` status
- Each activity could be connected to multiple targets. Targets must be in type of activity.
    - An activity could have no target. It means process is in last activity state.
- Each activity has its current status. statuses may be between one of these statuses: `active`, `inactive`, `done`.
- At the start of process all activities have `inactive` status.
- When activity target is triggered, the target activity goes to `active` status.
- Each process only could have one `active` activity at a time.
- Each activity could have its own observers. while updating activity status, observer will be notified.
- This engine does not support conditional or parallel flows.

# Installation

Simply run composer require command to include this library in your project
```shell script
    composer require mehrdadmahdian/php-workflow-core
```

to access to library feature, this namespace should be used: `MehrdadMahdian\PhpWorkflowCore`

<!-- USAGE EXAMPLES -->
# Usage

An example of process configuration array is introduced here. This is a process with 3 activities which is not started yet and all activities has null or inactive status.
![alt Simple Process Example](simple.jpg)
Code Client decided where to load configuration. It can be loaded from permanent storage, or it could be loaded statically form a file.  
```php
    $configuration = [
       'activities' => [
           [
               'name' => 'act1',
               'sources' => [],
               'targets' => ['act2'],
//               'status'  => ElementInterface::STATUS_INACTIVE,
               'observers' => [ActivitySampleObserver::class],
               'extra-actions' => [
                    SomeActionTypeWhichImplementsActionInterface1::class,
                    SomeActionTypeWhichImplementsActionInterface2::class
                ]           
           ],
           [
               'name' => 'act2',
               'sources' => ['act1'],
               'targets' => ['act3'],
//               'status'  => ElementInterface::STATUS_INACTIVE
           ],
           [
               'name' => 'act3',
               'sources' => ['act2'],
               'targets' => [],
               'status'  => ElementInterface::STATUS_INACTIVE
           ],
       ]
    ];
```

Process model could be built using package built-in facade method.

```php
    use MehrdadMahdian\PhpWorkflowCore\PhpWorkflowCoreFacade;
    $model = PhpWorkflowCoreFacade::buildProcessModel($configuration);
```

## Actions
client could run engine action using built-in facade too:

```php
    use MehrdadMahdian\PhpWorkflowCore\PhpWorkflowCoreFacade;
    $model = PhpWorkflowCoreFacade::runEngineAction($model, $action, $params);
```
after each action type, updated model is accessible. Updated model data must be persisted by client if it is needed.

to find thant which actions each activity has, we can use this code: 
```php
    use MehrdadMahdian\PhpWorkflowCore\PhpWorkflowCoreFacade;
    $model = PhpWorkflowCoreFacade::getActivityActions($model, $myActivityKey);
```
it will return list of available actions with their required parameters. 


Two built-in actions are supported in this library and each one has its own params.
### Start Action
No Parameter is needed in this type of action
```php
    use MehrdadMahdian\PhpWorkflowCore\PhpWorkflowCoreFacade;
    $model = PhpWorkflowCoreFacade::runEngineAction(
        $model, //suppose that model is defined previously in the code. mdoel is in type of ModelInterface 
        'start'
    );
```

### Transition Action
```php
    use MehrdadMahdian\PhpWorkflowCore\PhpWorkflowCoreFacade;
    $model = PhpWorkflowCoreFacade::runEngineAction(
        $model,
        'transition',
        ['currentActivityKey' => 'act1', 'targetActivityKey' => 'act2']
     );
```

## Extra Actions
Inside of built in actions of workflow core, we can run desired action which is implements `ActionInterface`.
To do that, action class must be fed to `runEngineAction` like this:
```php
    use MehrdadMahdian\PhpWorkflowCore\PhpWorkflowCoreFacade;
    $parameters = [
        //key: //value,
        //key2: //value2,
        ...
    ];         
    $model = PhpWorkflowCoreFacade::runEngineAction(
        $model,
        \Path\To\My\Custom\Action::class,
        $parameters
     );
```

## Observers
No description yet.

<!-- CONTRIBUTING -->
# Contributing

if you want to contribute in this project please follow this instruction.

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a PR on this repository
6. Wait to accept and merge the PR

<!-- LICENSE -->
# License

Distributed under the MIT License.

<!-- CONTACT -->
# Contact

Mehrdad Mahdian: [Gmail](escherchia88@gmail.com)

Project Link: [PHP Workflow Core](https://github.com/escherchia/process-engine-core)


# Todo
- missing tests
- get available actions from activity
- support published configurations file. 
- configuration validator
- action validator implementation

# Suggested Features to contribute
- wrapper for laravel and other php frameworks
