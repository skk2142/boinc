<?php
require_once("docutil.php");

page_head("Compound applications");

echo "
<h3>Compound applications</h3>

A <b>compound application</b> consists of a <b>main program</b>
and one or more <b>worker programs</b>.
The main program executes the worker programs in sequence.
It maintains a state file that records
which subsidiary programs have already completed.
It assigns to each worker application
a subrange of the overall 'fraction done' range of 0..1.
For example, if there are two subsidiary applications
with equal runtime,
the first would have range 0 to 0.5 and the second
would have range 0.5 to 1.


<p>
The BOINC API provides a number of functions,
and in developing a compound application you must decide
whether these
how these functions are to be divided between
the main and worker programs.

<pre>
struct BOINC_OPTIONS {
};
<p>
The main program logic is as follows:
<pre>
boinc_init_options(...)
read state file
for each remaining subsidiary application:
    boinc_parse_init_data_file()
    aid.fraction_done_start = x
    aid.fraction_done_end = y
    boinc_write_init_data_file()
    run the app
    write state file
exit(0)
</pre>
where x and y are the appropriate fraction done range limits.

<p>
Each subsidiary program should use the normal BOINC API,
including calls to boinc_fraction_done()
with values ranging from 0 to 1.

<p>
If the graphics is handled in a program that runs concurrently with
the subsidiary applications, it can call
<code>boinc_init(false)</code> to designate it as a non-worker thread.
This program can then use the BOINC graphics API, but not the API
calls that handle checkpointing and status updates to BOINC.
It must call <code>boinc_finish(false)</code> to terminate.


";
page_tail();
?>
