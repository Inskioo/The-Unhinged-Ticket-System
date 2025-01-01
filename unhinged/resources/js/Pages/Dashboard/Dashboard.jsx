import React, { useState, useEffect } from 'react';

import Navigation from './Components/Navigation';
import ActionBar from './Components/ActionBar';
import TicketList from './Components/Queue/List';
import TicketDetail from './Components/Queue/Detail';
import FilterPanel from './Components/Filters/Panel';
import StatsPanel from './Components/Stats/Panel';

const Dashboard = () => {
    const [currentView, setCurrentView] = React.useState('tickets');
    const [selectedTicket, setSelectedTicket] = useState(null);
    const [supportAgents, setSupportAgents] = useState([]);
    const [refreshTrigger, setRefreshTrigger] = useState(0);
    
    const handleViewChange = (view) => {
        setCurrentView(view);
        setSelectedTicket(null);
    };

    const [filters, setFilters] = useState({
        assignment: null,
        status: null,
        type: null,
        priority: null,
        supportAgent: null,
        customerSearch: null,
    });

    const handleFilterChange = (filterType, value) => {
        setFilters(prev => ({
            ...prev,  
            [filterType]: value  
        }));
    };

    const handleTicketUpdate = () => {
        setRefreshTrigger(prev => prev + 1);
    };

    useEffect(() => {
        const fetchAgents = async () => {
            try {
                const response = await fetch('/api/users/support');
                const data = await response.json();
                setSupportAgents(data);
            } catch (error) {
                console.error('Error fetching support agents:', error);
            }
        };
        
        fetchAgents();
    }, []); 

    useEffect(() => {
        const interval = setInterval(() => {
            setRefreshTrigger(prev => prev + 1);
        }, 10000);
    
        return () => clearInterval(interval);
    }, []);

    const handleTicketSelect = (ticket) => {
        setSelectedTicket(ticket);
    };

    const renderPanel = () => {
        if (selectedTicket) { 
            return (
                <TicketDetail 
                    ticket={selectedTicket}
                    supportAgents={supportAgents}
                    onClose={() => setSelectedTicket(null)}
                    onUpdate={handleTicketUpdate}
                />
            );
        }
        return currentView === 'stats' ? 
            <StatsPanel 
                supportAgents={supportAgents}
            /> : 
            <FilterPanel
                filters={filters}
                onTicketSelect={handleTicketSelect}
                refreshTrigger={refreshTrigger}
                agentsList={supportAgents}
                onFilterChange={handleFilterChange}
            />;
    };

    return (
        <div className="dashboard">
            <div className="mainPanel">
                <Navigation />
                <ActionBar 
                    currentView={currentView}
                    handleViewChange={handleViewChange}
                />
                <div className="panel">
                    {renderPanel()}
                </div>
            </div>
            <div className="masterList">
                <TicketList
                    filters={filters}
                    onTicketSelect={handleTicketSelect}
                    refreshTrigger={refreshTrigger}
                    supportAgents={supportAgents}
                />
            </div>
        </div>
    );
};

export default Dashboard;