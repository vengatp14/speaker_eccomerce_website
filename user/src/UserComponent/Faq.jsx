import React, { useState } from 'react';
import { Accordion, AccordionSummary, AccordionDetails, Typography, Container, Grid } from '@mui/material';
import ExpandMoreIcon from '@mui/icons-material/ExpandMore';

const FaqItem = ({ question, answer }) => {
  return (
    <Accordion>
      <AccordionSummary
        expandIcon={<ExpandMoreIcon />}
        aria-controls="panel1a-content"
        id="panel1a-header"
      >
        <Typography variant="h6">{question}</Typography>
      </AccordionSummary>
      <AccordionDetails>
        <Typography>{answer}</Typography>
      </AccordionDetails>
    </Accordion>
  );
};

const Faq = () => {
  const faqData = [
    {
      question: 'What services does Madurai Dhaksh Tours offer?',
      answer: 'Madurai Dhaksh Tours and Travels offers city tours, airport transfers, outstation trips, and customized travel packages tailored to your needs.',
    },
    {
      question: 'What types of vehicles are available in your fleet?',
      answer: 'Our fleet includes sedans, SUVs, luxury cars, and minibuses for various group sizes and travel requirements.',
    },
    {
      question: 'Are your drivers experienced and licensed?',
      answer: 'Yes, all our drivers are licensed, professionally trained, and prioritize passenger safety and comfort.',
    },
    {
      question: 'How are your rates calculated?',
      answer: 'Rates are based on the type of vehicle, duration, and distance. We offer transparent pricing with no hidden charges.',
    },
    {
      question: 'What is your cancellation policy?',
      answer: 'Cancellations made 24 hours in advance are eligible for a full refund. Less than 24 hours may incur a cancellation fee.',
    },
    {
      question: 'Do you provide outstation trips?',
      answer: 'Yes, we offer reliable outstation trips with experienced drivers for long-distance travel.',
    },
  ];

  return (
    <section id="faq" className="faq section light-background">
      <Container maxWidth="lg">
        <Typography variant="h4" align="center" gutterBottom>
          Frequently Asked Questions
        </Typography>
        <Grid container spacing={2}>
          {faqData.map((item, index) => (
            <Grid item xs={12} md={6} key={index}>
              <FaqItem question={item.question} answer={item.answer} />
            </Grid>
          ))}
        </Grid>
      </Container>
    </section>
  );
};

export default Faq;
