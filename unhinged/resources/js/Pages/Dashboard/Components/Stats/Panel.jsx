import React, { useState, useEffect } from 'react';

const StatsPanel = () => {
    const [queueStats, setQueueStats] = useState({

        currentQueue: {
            total: 0,
            unassigned: 0,
            assignedIncomplete: 0
        },

        typeBreakdown: {
            slightlyUnhinged: 0,
            wildlyUnhinged: 0
        },

        resolvedStats: {
            totalComplete: 0,
            slightlyUnhinged: 0,
            wildlyUnhinged: 0
        }
    });

    const [agentStats, setAgentStats] = useState([]);

    // Local Cache
    useEffect(() => {
        const savedQueueStats = localStorage.getItem('queueStats');
        const savedAgentStats = localStorage.getItem('agentStats');
        
        if (savedQueueStats) setQueueStats(JSON.parse(savedQueueStats));
        if (savedAgentStats) setAgentStats(JSON.parse(savedAgentStats));
    }, []);

    // Get Stats :) 
    useEffect(() => {
        const fetchStats = async () => {
            try{
                const queueResponse = await fetch('/api/tickets/stats/queue');
                const queueData = await queueResponse.json();
                setQueueStats(queueData);

                const agentResponse = await fetch('/api/tickets/stats/agents');
                const agentData = await agentResponse.json();
                setAgentStats(agentData);

                localStorage.setItem('queueStats', JSON.stringify(queueData));
                localStorage.setItem('agentStats', JSON.stringify(agentData));

            } catch (error) {
                console.error('Oh Statseo, Stateo, Where art thou', error);
            }
        };

        fetchStats(); 
        const refresh = setInterval(fetchStats, 10000);
        return () => clearInterval(refresh);
        
    }, []);

    return (
        <div className="stats">
            <div className="queue">
                <h2>Queue Stats</h2>
                <div className="statsArray">
                    <p className="figure">Currently In Queue: <span>{queueStats.currentQueue.total}</span></p>
                    <p className="figure">Unassigned: <span>{queueStats.currentQueue.unassigned}</span></p>
                    <p className="figure">Assigned Incomplete: <span>{queueStats.currentQueue.assignedIncomplete}</span></p>
                    <p className="figure">Slightly Unhinged: <span>{queueStats.typeBreakdown.slightlyUnhinged}</span></p>
                    <p className="figure">Wildly Unhinged: <span>{queueStats.typeBreakdown.wildlyUnhinged}</span></p>
                </div>
            </div>
            <div className="queue">
                <h2>Resolved Stats</h2>
                <div className="statsArray">
                    <p className="figure">Total Complete: <span>{queueStats.resolvedStats.totalComplete}</span></p>
                    <p className="figure">Slightly Unhinged: <span>{queueStats.resolvedStats.slightlyUnhinged}</span></p>
                    <p className="figure">Wildly Unhinged: <span>{queueStats.resolvedStats.wildlyUnhinged}</span></p>
                
                </div>
            </div>
            <div className="queue agentQueue">
                <h2>Agent Stats</h2>
                <div className="agentArray">
                    {agentStats.map(agent => (
                        <div key={agent.id} className="agent">
                            <p className="figure">Agent Name: <span>{agent.name}</span></p>
                            <p className="figure">Assigned: <span>{agent.assignedCount}</span></p>
                            <p className="figure"><span>{agent.qtr}</span>% QTR</p>
                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
}

export default StatsPanel;