<?php

namespace App\Http\Controllers;

use App\Analysis;
use App\AnalysisChart;
use App\DashboardChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;

class AnalyticsDashboardController extends Controller
{
    private $analysis;
    private $chart;
    private $dchart;

    /**
     * AnalysisController constructor.
     * @param Analysis $analysis
     * @param AnalysisChart $chart
     * @param DashboardChart $dchart
     */
    public function __construct(Analysis $analysis, AnalysisChart $chart, DashboardChart $dchart)
    {
        // We do this dependency injection so it's easier to mock during tests
        $this->analysis = $analysis;
        $this->chart = $chart;
        $this->dchart = $dchart;
    }

    public function index()
    {
        $charts = $this->dchart
            ->with('chart.type', 'chart.labels')
            ->orderBy('position', 'asc')
            ->get();
        return view('pages.analytics.dashboard.index', compact('charts'));
    }

    public function add()
    {
        $analyses = $this->analysis->has('charts')->get();
        return view('pages.analytics.dashboard.add', compact('analyses'));
    }

    /**
     * Move a chart on the dashboard
     * Nice to have: Ajax call to move it
     * @param int $id
     * @param int $oldpos
     * @param int $newpos
     * @return \Illuminate\Http\RedirectResponse
     */
    public function move($id, $oldpos, $newpos)
    {
        $chart = $this->dchart->findOrFail($id);
        $modifier = ($oldpos <= $newpos) ? '>=' : '<=';

        $next = $this->dchart->where('position', $modifier, $chart->position)->orderBy('position', 'asc')->take(1)->first();

        if ($next === null) {
            return redirect()
                ->back()
                ->withErrors([Lang::get('dashboard.cant-move-further')]);
        }

        \DB::transaction(function() use ($chart, $next) {
            $oldChartpos = $chart->position;
            $chart->position = $next->position;
            $next->position = $oldChartpos;

            $chart->save();
            $next->save();
            return redirect()
                ->back()
                ->with('success', 'Chart has been moved.');
        });
        return redirect()
            ->back()
            ->withErrors([Lang::get('dashboard.failed-to-move')]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'position' => 'required|numeric',
            'chart_id' => 'required|exists:chart,id'
        ]);
        $data = $request->all();

        $dchart = new $this->dchart($data);
        $dchart->chart_id = $data['chart_id'];
        if (!$dchart->save())
            return redirect()
                ->back()
                ->withErrors(['error', Lang::get('dashboard.chart-added-fail')]);

        return redirect()->route('dashboard.index')->with('success', Lang::get('dashboard.chart-added'));
    }

    public function destroy($id)
    {
        $chart = $this->dchart->findOrFail($id);
        if (!$chart->delete())
            return redirect()
                ->back()
                ->withErrors(['error', Lang::get('dashboard.chart-removed-fail')]);

        return redirect()->back()
            ->with('success', Lang::get('dashboard.chart-removed'));
    }
}