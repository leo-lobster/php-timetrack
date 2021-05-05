<?php
class timetracker {
    private $starttime;
    private $timestamps = array();
    private $counter;

    public function __construct($description = "start reading"){
        $this->starttime = round(microtime(1)*1000, 2);
        $this->counter = 0;
        $this->add($description);
    }

    public function add($description = ""){
        $this->timestamps[] = array($this->counter, $description, round((microtime(1)*1000-$this->starttime), 2));
        $this->counter++;
    }

    public function logTimestamps(){
        highlight_string("<?php\n\$timestamps =\n" . var_export($this->timestamps, true) . ";\n?>");
    }

    public function logPeriods(){
      $durations = array();
        
      for($i = 1; $i < $this->counter; $i++) {
        $time = round($this->timestamps[$i][2]-$this->timestamps[$i-1][2], 2);
        $percentage = round($time/($this->timestamps[$this->counter-1][2] - $this->timestamps[0][2]), 2);
        $description = $this->timestamps[$i-1][1] . " --> " . $this->timestamps[$i][1];
        $durations[] = [$i, $percentage, $time, $description];
      }

      highlight_string("<?php\n\$Periods =\n" . var_export($durations, true) . ";\n?>");
  }

    public function htmlOut(){
        echo("<style>.time-bar {width: 100%;max-width: 800px;border: 1px solid;}.time-bar > div {min-width: 8px;}.time-bar_process {display: inline-block;text-align: center;padding: 4px 0 4px 0;}.time-bar_process:nth-child(odd) {background: #000;color: #fff;}.time-table {width: 100%;max-width: 800px;border: 1px solid black;margin-top: 2rem;}.time-tabletable, .time-table th, .time-table td {border: 1px solid black;padding: 2px;}</style>");
        $durations = array();
        
        for($i = 1; $i < $this->counter; $i++) {
           $time = round($this->timestamps[$i][2]-$this->timestamps[$i-1][2], 2);
           $percentage = round($time/($this->timestamps[$this->counter-1][2] - $this->timestamps[0][2]), 2);
           $description = $this->timestamps[$i-1][1] . " --> " . $this->timestamps[$i][1];
           $durations[] = [$i, $percentage, $time, $description];
        }
        echo('<div class="time-bar">');
        
        for($i = 0; $i<count($durations); $i++) {
          echo('<div style="width: ' . $durations[$i][1]*100 . '%" class="time-bar_process">' . $durations[$i][0] . '</div>');
        }
        echo('</div>');

        echo('<table class="time-table"><thead><tr><th>Interval</th><th>Percentage</th><th>Time</th><th>Description</th></tr></thead><tbody>');
        
        for($i = 0; $i<count($durations); $i++) {
          echo("<tr><td>" . $durations[$i][0] . "</td><td>" . $durations[$i][1]*100 . "%</td><td>" . $durations[$i][2] . "ms</td><td>" . $durations[$i][3] . "</td></tr>");
        }
  
        echo('</tbody></table>');
        echo("<br><p>Total Execution-Time: " . round(($this->timestamps[$this->counter-1][2] - $this->timestamps[0][2]), 2) . "ms</p>");
        

    }
}
