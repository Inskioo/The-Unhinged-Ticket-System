import React, { useState } from 'react';

// Parent Components
import Navigation from './Components/Navigation';
import ActionBar from './Components/ActionBar';

// Listing Related Components
//import TicketList from '/Components/Queue/List';
//import TicketDetail from '/Components/Queue/Detail';
//import TicketItem from '/Components/Queue/Item';

// Filters
import FilterPanel from './Components/Filters/Panel';
//import FilterItem from '/Components/Filters/Item';

// Stats Pane
import StatsPanel from '/Components/Stats/Panel';
//import StatsItem from '/Components/Stats/Item';

const Dashboard = () => {
    const [currentView, setCurrentView] = React.useState('tickets');

    const handleViewChange = (view) => {
        setCurrentView(view);
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

    return (
        <div className="dashboard">
            <div className="mainPanel">
                <Navigation />
                <ActionBar 
                    currentView={currentView}
                    handleViewChange={handleViewChange}
                />
                <div className="panel">
                    <FilterPanel 
                        filters={filters}
                        onFilterChange={handleFilterChange}
                    />
                </div>
            </div>
        </div>
    );
};

export default Dashboard;
