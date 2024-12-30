import React, { useState, useEffect } from 'react';

const FilterPanel = ({ filters, onFilterChange }) => {
    const pees = [
        'p1', 'p2', 'p3', 'p4'
    ];

    const [supportAgents, setSupportAgents] = useState([]);
    const [searchTerm, setSearchTerm] = useState('');

    useEffect(() => {
        const fetchSupportHoomans = async () => {
            try {
                const response = await fetch('/api/users/support');
                const data = await response.json();
                setSupportAgents(data);
            } catch (error) {
                console.error('Error fetching support hoomans, sad :(', error);
            }
        };

        fetchSupportHoomans();
    }, []);

    return (
        <div className="filters">
            <h2>Find and Filter Unhinged Tickets You're Interested In</h2>
            
            <div className="section">
                <h3>Assignment</h3>
                <span 
                    onClick={() => onFilterChange('assignment', filters.assignment === 'unassigned' ? null : 'unassigned')}
                    className={`${filters.assignment === 'unassigned' ? 'active' : ''}`}
                >
                    Unassigned
                </span>
                <span 
                    onClick={() => onFilterChange('assignment', filters.assignment === 'assigned' ? null : 'assigned')}
                    className={`${filters.assignment === 'assigned' ? 'active' : ''}`}
                >
                    Assigned
                </span>
            </div>

            <div className="section">
                <h3>Status</h3>
                <span 
                    onClick={() => onFilterChange('status', filters.status === 'resolved' ? null : 'resolved')}
                    className={`${filters.status === 'resolved' ? 'active' : ''}`}
                >
                    Resolved
                </span>
                <span 
                    onClick={() => onFilterChange('status', filters.status === 'unresolved' ? null : 'unresolved')}
                    className={`${filters.status === 'unresolved' ? 'active' : ''}`}
                >
                    Unresolved
                </span>
            </div>

            <div className="section">
                <h3>How Unhinged</h3>
                <span 
                    onClick={() => onFilterChange('type', filters.type === 'slightly_unhinged' ? null : 'slightly_unhinged')}
                    className={`${filters.type === 'slightly_unhinged' ? 'active' : ''}`}
                >
                    Slightly Unhinged
                </span>
                <span 
                    onClick={() => onFilterChange('type', filters.type === 'wildly_unhinged' ? null : 'wildly_unhinged')}
                    className={`${filters.type === 'wildly_unhinged' ? 'active' : ''}`}
                >
                    Wildly Unhinged
                </span>
            </div>

            <div className="section">
                <h3>Priority</h3>
                {pees.map((pee) => (
                    <span 
                        key={pee}
                        onClick={() => onFilterChange('priority', filters.priority === pee ? null : pee)}
                        className={`${filters.priority === pee ? 'active' : ''}`}
                    >
                        {pee}
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
                    {supportAgents.map(agent => (
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
                        setSearchTerm(e.target.value);
                        onFilterChange('customerSearch', e.target.value || null);
                    }}
                    className="searchInput"
                />
            </div>
        </div>
    );
};

export default FilterPanel;