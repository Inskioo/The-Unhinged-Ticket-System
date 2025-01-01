import React, { useState, useEffect } from 'react';

const FilterPanel = ({ filters, onFilterChange, agentsList }) => {
    const pees = [
        'p1', 'p2', 'p3', 'p4'
    ];

    const [searchTerm, setSearchTerm] = useState(filters.customerSearch || '');

    const getActiveClass = (filterType, value) => {
        return filters[filterType] === value ? 'active' : '';
    };

    return (
        <div className="filters">
            <h2>Find and Filter Unhinged Tickets You're Interested In</h2>
            
            <div className="section">
                <h3>Assignment</h3>
                <span 
                    onClick={() => onFilterChange('assignment', 
                        filters.assignment === 'unassigned' ? null : 'unassigned')}
                    className={getActiveClass('assignment', 'unassigned')}
                >
                    Unassigned
                </span>
                <span 
                    onClick={() => onFilterChange('assignment', 
                        filters.assignment === 'assigned' ? null : 'assigned')}
                    className={getActiveClass('assignment', 'assigned')}
                >
                    Assigned
                </span>
            </div>

            <div className="section">
                <h3>Status</h3>
                <span 
                    onClick={() => onFilterChange('status', 
                        filters.status === 'resolved' ? null : 'resolved')}
                    className={getActiveClass('status', 'resolved')}
                >
                    Resolved
                </span>
                <span 
                    onClick={() => onFilterChange('status', 
                        filters.status === 'unresolved' ? null : 'unresolved')}
                    className={getActiveClass('status', 'unresolved')}
                >
                    Unresolved
                </span>
            </div>

            <div className="section">
                <h3>How Unhinged</h3>
                <span 
                    onClick={() => onFilterChange('type', 
                        filters.type === 'slightly_unhinged' ? null : 'slightly_unhinged')}
                    className={getActiveClass('type', 'slightly_unhinged')}
                >
                    Slightly Unhinged
                </span>
                <span 
                    onClick={() => onFilterChange('type', 
                        filters.type === 'wildly_unhinged' ? null : 'wildly_unhinged')}
                    className={getActiveClass('type', 'wildly_unhinged')}
                >
                    Wildly Unhinged
                </span>
            </div>

            <div className="section">
                <h3>Priority</h3>
                {pees.map((pee) => (
                    <span 
                        key={pee}
                        onClick={() => onFilterChange('priority', 
                            filters.priority === pee ? null : pee)}
                        className={getActiveClass('priority', pee)}
                    >
                        {pee.toUpperCase()}
                    </span>
                ))}
            </div>

            <div className="section">
                <h3>By Agent</h3>
                <select
                    value={filters.supportAgent || ''}
                    onChange={(e) => onFilterChange('supportAgent', e.target.value || null)}
                >
                    <option value="">Select Support Agent</option>
                    {agentsList?.map(agent => ( 
                        <option key={agent.id} value={agent.id}>
                            {agent.name}
                        </option>
                    ))}
                </select>
            </div>

            <div className="section">
                <h3>Search by Customer</h3>
                <input
                    type="text"
                    placeholder="Search by name..."
                    value={searchTerm}
                    onChange={(e) => {
                        const value = e.target.value;
                        setSearchTerm(value);
                        onFilterChange('customerSearch', value || null);
                    }}
                    className="searchInput"
                />
            </div>
        </div>
    );
};

export default FilterPanel;