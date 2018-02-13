# Run unit tests.
echo '## Running unit tests:'
phpunit --testsuite unit

# Run integration tests.
echo '## Running integration tests:'
phpunit --testsuite unit

# Run integration tests.
echo '## Running integration tests:'
export PHPUNIT_CONFIG=tests/PhpUnit/Integration/phpunit.xml.dist
run_phpunit_travisci