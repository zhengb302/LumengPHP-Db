# todo list

* Model类增加inc、dec方法

#### 2017-06-08

* ~~Repository增加findValue和findColumn方法~~
* ~~Repository查询增加distinct支持~~
* ~~Repository改名为Model~~
* ~~Model类增加getConnection方法~~
* ~~Model类创建方式改写~~

#### 2017-06-07

* ~~设计并实现新的查询过滤语言~~
* ~~Repository增加insertAll方法，以支持数据的批量插入~~
* ~~增加对GROUP BY、HAVING子句的支持~~
* ~~orderBy支持以关联数组的方式设置排序子句~~

#### 2016-03-27

* ~~增加数据库事务操作相关的测试用例~~
* ~~创建1.0分支，发布1.0.0版~~

#### 2016-03-26

* ~~支持Logger组件，用以进行数据库相关操作的日志记录~~
* ~~完善测试环境，增加必要的测试用例~~

#### 2016-03-19

* ~~选择数据库连接时，不再以具体的表作为选择参数，即：如果配置了读写分离，则写操作一直都在master进行，读操作一直都在slave上进行。~~
* ~~提供相应的接口可以让用户选择读操作都在master上进行。~~
* ~~进入事务状态时，所有读写操作必须都在master进行。~~
* ~~ConnectionGroup改名为Connection，组名对应到连接名称。~~
* ~~数据库配置改为dsn的方式，避免引入type配置参数，从而避免在创建连接时需要区分不同的DBMS~~
* ~~用户接口优化。~~
* ~~在数据库配置中加入charset支持。~~
* ~~去掉对LumengPHP-Utils项目的依赖。~~
* ~~增加对EXISTS、NOT EXISTS条件子句的支持~~
* ~~完成代码注释中的各todo项~~

#### 2016-03-15

* ~~各方法的成功返回值、失败返回值设计~~

#### 2016-03-14

* ~~完成orderBy、paging、limit方法~~
