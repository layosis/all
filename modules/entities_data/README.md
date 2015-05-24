Drupal 7 entities_data
=================

The purpose of this project is to provide to  drupal programmers an easy way to access an save datas located in drupal entities.

### Done
* Queries to 1 Single node entity.
* Queries to 1-1 nodes entities.
* Queries to 1-n nodes entities.
* Set datas in a single node entity


### Usage
Include this code in your module
```bash
  //example get datas from node with entity relation (address)
  $id = "12345";
  $partner = EntitiesData::getDatas('node', 'people', "first_name last_name adress(street number)", "id=$id");
  //this return a array of arrays

  //exmplate to set datas in a node
  $date = date("Y-m-d H:i:s");
  EntitiesData::setDatas('node', '51', "first_name,birthday", "Osvaldo,$date");
```

### To do
Queries for get:
* n-1 relations.
* n-n relations.

Queries for set:
* 1 relations.
* 1-1 relations.
* 1-n relations.
* n-1 relations.
* n-m relations.

Set datas into:
* Nodes with relations
* Other entities type
