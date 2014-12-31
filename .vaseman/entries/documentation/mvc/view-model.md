layout: documentation.twig
title: View Model

---

# About MVC Interactions

In most MVC practices, there is no direct communication between View and Model. We called it **Passive View**. But there are many 
variants of MVC like: MVVM, MVP etc. 

Windwalker uses a pattern which is similar to [Supervising Controller](http://goo.gl/p6Rjwl), [MVVM](http://goo.gl/LJPG) or [MVP](http://goo.gl/y3VzE)
, View can not communicate to Model, but Controller can binding Model to View, then View is able to get data from Model.

The benefit of this pattern is that View can decide which data they are needed and call Model to get these data.
 And controller's responsibility is just decide binding which models to view. There is a `ViewModel` between View and Model 
 to handle request from View and get data from Model.

![](http://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/ModelViewControllerDiagram2.svg/350px-ModelViewControllerDiagram2.svg.png)
