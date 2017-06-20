# Website Quiz english use wordpress

[1. fgc-quiz: plugin use table to store data](#fgc-quiz)

[2. fgc-quiz-custom-post: Plugin use custom post type to store data](#fgc-quiz-custom-post)

## fgc-quiz

    Plugin use table to store data

* Feature

1. Manager class: add, edit, delete Class
    1. List class: List class in database, count number member in class. Link to view, edit, delete class

        ![Manager class](/fgc-quiz/doc/images/Capture1.PNG)

    1. View members in a class: allow remove user from class

        ![Manager class](/fgc-quiz/doc/images/Capture2.PNG)

    1. Edit a class: allow rename, public or private class.

        ![Manager class](/fgc-quiz/doc/images/edit-class.gif)

    1. Add a user to a class

        ![Manager class](/fgc-quiz/doc/images/add-member.gif)

        or

        ![Manager class](/fgc-quiz/doc/images/edit-user.gif)

1. Manger timetable: allow view, edit timetable of class
    1. List timetable: see time update timetable. Link to view detail, edit.

        ![Manager class](/fgc-quiz/doc/images/timetable1.PNG)

    1. Edit timetable of a class: When edit timetable, if have different will save history of timetable.

        ![Manager class](/fgc-quiz/doc/images/edit-timetable.gif)

    1. View timetable of a class

        ![Manager class](/fgc-quiz/doc/images/timetable3.PNG)

    1. List timetable in page or post: use shortcode to insert timetable to post, page.

        ```[timetable]``` : Auto show timetable by user class logged belong to. If is admin, show all timetable of classes.

        ```[timetable classname="A1"]``` : Show timetable of class name: A1

        ![Manager class](/fgc-quiz/doc/images/timetable4.PNG)

1. Manager game: use for insert game in post, page via shortcode.

        [game url="http://.../game.swf"] : Insert game by url.

        [game id="1"] : Insert game by id in database.

    1. List game

        ![Manager class](/fgc-quiz/doc/images/game1.PNG)

    1. Edit a game

        ![Manager class](/fgc-quiz/doc/images/edit-game.gif)

1. Manager post / page by class
    1. Add column class in list post / page

        ![Manager class](/fgc-quiz/doc/images/post.png)

    1. Set post / page belong to which class.

        ![Manager class](/fgc-quiz/doc/images/post2.png)

    1. If post or page set only for a class, other user in other class will cannot access that post / page

        ![Manager class](/fgc-quiz/doc/images/post3.PNG)

## fgc-quiz-custom-post

    Plugin use custom post type to store data

* Feature

1. List class: add, edit, delete Class
    1. List class

        ![Manager class](/fgc-quiz/doc/images/class-post1.PNG)

    1. Add new class

        ![Manager class](/fgc-quiz/doc/images/add-class-post.gif)

    1. Add, remove member from class (use ajax)

        ![Manager class](/fgc-quiz/doc/images/add-use.gif)
