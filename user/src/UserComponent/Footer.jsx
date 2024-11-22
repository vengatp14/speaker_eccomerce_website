import React from 'react';
import { Box, Typography, Grid, Link, IconButton } from '@mui/material';
import FacebookIcon from '@mui/icons-material/Facebook';
import TwitterIcon from '@mui/icons-material/Twitter';
import InstagramIcon from '@mui/icons-material/Instagram';
import LinkedInIcon from '@mui/icons-material/LinkedIn';

const Footer = () => {
  return (
    <Box
      sx={{
        bgcolor: 'primary.main',
        color: 'white',
        p: 5,
        mt: 5,
      }}
    >
      <Grid container spacing={3}>
        {/* Company Info */}
        <Grid item xs={12} md={6}>
          <Typography variant="h6" gutterBottom>
          Plugcrux
          </Typography>
          <Typography variant="body2">
            123  nehruStreet, chennai, India
            <br />
            Email: info@plugcrux.com
            <br />
            Phone: +91 9876543210
          </Typography>
        </Grid>

        {/* Useful Links */}
        <Grid item xs={12} md={4}>
          <Typography variant="h6" gutterBottom>
            Useful Links
          </Typography>
          <Link href="/" color="inherit" variant="body2" underline="hover">
            Home
          </Link>
          <br />
          <Link href="/products" color="inherit" variant="body2" underline="hover">
            Products
          </Link>
          <br />
          <Link href="/about" color="inherit" variant="body2" underline="hover">
            About Us
          </Link>
          <br />
          <Link href="/contact" color="inherit" variant="body2" underline="hover">
            Contact Us
          </Link>
        </Grid>

        {/* Social Media */}
        <Grid item xs={12} md={2}>
          <Typography variant="h6" gutterBottom>
            Follow Us
          </Typography>
          <IconButton
            href="https://www.facebook.com"
            target="_blank"
            color="inherit"
          >
            <FacebookIcon />
          </IconButton>
          <IconButton
            href="https://www.twitter.com"
            target="_blank"
            color="inherit"
          >
            <TwitterIcon />
          </IconButton>
          <IconButton
            href="https://www.instagram.com"
            target="_blank"
            color="inherit"
          >
            <InstagramIcon />
          </IconButton>
          <IconButton
            href="https://www.linkedin.com"
            target="_blank"
            color="inherit"
          >
            <LinkedInIcon />
          </IconButton>
        </Grid>
      </Grid>

      <Box textAlign="center" mt={3}>
        <Typography variant="body2">
          © {new Date().getFullYear()} Plugcrux All rights reserved.
        </Typography>
         <Typography variant="body2">
          @Developed by SDGD.
        </Typography>
      </Box>
    </Box>
  );
};

export default Footer;
