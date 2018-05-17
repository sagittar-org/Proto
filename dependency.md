| hahdler              | columns                           | dependents                                                   |
|----------------------|-----------------------------------|--------------------------------------------------------------|
| config               | db, language, actors              |                                                              |
| actual_database      | tables, references                |                                                              |
| application_database | tables,references                 |                                                              |
| request_database     | tables,references                 | actual_database, application_database                        |
| actual_table         | primary_keys, columns             |                                                              |
| application_table    | actors, aliases, actions, columns |                                                              |
| table_filter         | filter                            |                                                              |
| request_table        | actions, columns                  | actual_table, application_table, table_filter, request_table |
