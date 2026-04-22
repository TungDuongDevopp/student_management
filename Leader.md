### 3.5 Class
<pre>
@startuml
skinparam linetype ortho

class Role
class Account
class Feedback
class Student
class Grade
class Attendance
class Classroom
class Enrollment
class Subject
class Faculty
class Teacher
class Tuition
class Payment
class Schedule
class "System config" as SystemConfig
class Room
class Semester

' Relationships
Role "1" -- "*" Account : Has
Account "1" -- "*" Feedback : Send
Teacher "1" -- "*" Feedback : Has

Student "1" -- "*" Enrollment : Create
Enrollment "1" *-- "1..*" Grade : Records
Enrollment "1" *-- "1..*" Attendance : Records

Enrollment "*" -- "1" Subject : Belong to
Subject "*" -- "1" Faculty : Belongs to
Faculty "1" o-- "*" Classroom : Has
Faculty "1" o-- "*" Teacher : Belongs to

Teacher "1..*" -- "1..*" Subject : Has
Student "1" -- "*" Classroom : Enrols
Classroom "1" -- "1..*" Teacher : Has

Subject "1..*" -- "1" Schedule : Has
Schedule "1" -- "1" Semester : Has
Schedule "1..*" -- "1" Room : uses

Student "1" -- "*" Tuition : Has
Tuition "1" *-- "1..*" Payment : Settles

@enduml
</pre>