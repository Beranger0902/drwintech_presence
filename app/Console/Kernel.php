
protected function schedule(Schedule $schedule)
{
    $schedule->command('feries:sync')->yearly();
}
