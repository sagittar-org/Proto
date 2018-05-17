| hahdler              | columns                           | dependents                                                               |
|----------------------|-----------------------------------|--------------------------------------------------------------------------|
| config               | db, language, actors              |                                                                          |
| actual_database      | tables, references                |                                                                          |
| application_database | tables,references                 |                                                                          |
| filter_database      | filters                           |                                                                          |
| request_database     | tables,references                 | actual_database, application_database, filter_database                   |
| actual_table         | primary_keys, columns             |                                                                          |
| application_table    | actors, aliases, actions, columns |                                                                          |
| filter_table         | filters                           |                                                                          |
| request_table        | actions, primary_keys, columns    | request_database, actual_table, application_table, filter_table          |
