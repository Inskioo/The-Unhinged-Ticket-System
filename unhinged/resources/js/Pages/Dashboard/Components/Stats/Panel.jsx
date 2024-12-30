import React, { useState, useEffect } from 'react';

const StatsPanel => () {
    const [queueStats, setQueueStats] = useState({
        inQueue: 0,
        unassigned: 0,
        assignedIncomplete: 0,
        slightlyUnhinged: 0,
        wildlyUnhinged: 0,
    });

    const [agentStats, setAgentStats] = useState([]);

    // Get Stats :) 
    useEffect(() => async () => {
        try{
            const queueResponse = await fetch('/api/tickets/stats');
            const queueData = await queueResponse.json();
            setQueueStats(queueData);

            const agentResponse = await fetch('/api/tickets/stats/agents');
            const agentData = await agentResponse.json();
            setAgentStats(agentData);
        } catch {
            console.error('Oh Statseo, Stateo, Where art thou', error);
        }

        fetchStats();
    }, []);



}