<div id="tag_groups_task_<?php echo esc_attr($task) ?>" class="tag_groups_task_wrapper" style="display:none;">
  <h3 id="tag_groups_task_title_<?php echo esc_attr($task) ?>"><?php echo esc_html($tasks_whitelist[ $task ]) ?></h3>
  <div id="tag_groups_task_bar_wrapper_<?php echo esc_attr($task) ?>" class="tag_groups_process_bar_wrapper" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" aria-labelledby="tag_groups_task_title_<?php echo esc_attr($task) ?>">
    <div id="tag_groups_task_bar_<?php echo esc_attr($task) ?>" class="tag_groups_process_bar">
      <span style="margin:0 5px;" aria-hidden="true"><span id="tag_groups_task_progress_<?php echo esc_attr($task) ?>">0</span>%</span>
    </div>
  </div>
  <div id="tag_groups_task_result_<?php echo esc_attr($task) ?>" class="tag_groups_process_result">
  </div>
</div>
