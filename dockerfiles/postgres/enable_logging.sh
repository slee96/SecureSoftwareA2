#!/bin/bash
sed -i "/# FILE LOCATIONS/a \
log_statement = 'all' \
\nlog_directory = 'pg_log' \
\nlog_filename = 'postgresql-%Y-%m-%d_%H%M%S.log' \
\nlogging_collector = on \
\nlog_min_error_statement = error" /var/lib/postgresql/data/postgresql.conf
