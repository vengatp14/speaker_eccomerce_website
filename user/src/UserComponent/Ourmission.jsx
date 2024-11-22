import React from 'react';
import { Box, Typography, Card, CardContent, Avatar } from '@mui/material';
import VisibilityIcon from '@mui/icons-material/Visibility';
import CampaignIcon from '@mui/icons-material/Campaign';

const MissionVisionComponent = () => {
  return (
    <Box sx={{ p: 3, bgcolor: '#f5f5f5' }}>
      <Typography variant="h4" align='center' color='#2C3E50' gutterBottom>
      OUR MISSION AND VISION
      </Typography>
      
      <Box sx={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', mt: 4, flexWrap: 'wrap' }}>
        <Card sx={{ width: '30%', minWidth: 250, bgcolor: '#2c3e50', color: 'white', borderRadius: '30px 30px 30px 0', mb: 2 }}>
          <CardContent>
            <Avatar sx={{ bgcolor: '#e67e22', mb: 2 }}>
              <VisibilityIcon />
            </Avatar>
            <Typography variant="h6" gutterBottom>
              Vision
            </Typography>
            <Typography variant="body2">
              To become the largest website design firm by emphasizing innovative thinking to establish ourselves as a worldwide recognized company by offering the highest standard services and solutions
            </Typography>
          </CardContent>
        </Card>
        
        <Box
          component="img"
          sx={{
            width: '25%',
            minWidth: 300,
            maxHeight: 400,
            borderRadius: '90%',
            objectFit: 'cover',
            mb: 2
          }}
          alt="Team"
          src="/src/assets/img.png"
        />
        
        <Card sx={{ width: '30%', minWidth: 250, bgcolor: '#2c3e50', color: 'white', borderRadius: '30px 30px 0 30px', mb: 2 }}>
          <CardContent>
            <Avatar sx={{ bgcolor: '#e67e22', mb: 2 }}>
              <CampaignIcon />
            </Avatar>
            <Typography variant="h6" gutterBottom>
              Mission
            </Typography>
            <Typography variant="body2">
              To provide customer-centric, result-oriented, cost-competitive, innovative and functional IT solutions to our valuable worldwide customers
            </Typography>
          </CardContent>
        </Card>
      </Box>
    </Box>
  );
};

export default MissionVisionComponent;