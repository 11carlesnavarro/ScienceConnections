

#library(qdap)
library(dplyr)
library(easyPubMed)
library(devtools)
library(ggplot2)
library(tm)
library(RColorBrewer)
library(wordcloud)
library(wordcloud2)
library(tidytext)
library(tidyr)
library(tidyverse)
library(RISmed)
library(ggthemes)
#library(qdap)
library(magrittr)
#library(ggraph)
#library(igraph)
library(caret)
library(topicmodels)

args <- commandArgs(TRUE)
N <- args[1]


# make string with query
#x <- args[1]
#y <- " AND 2017 [DP] : 2018 [DP]"

#z <- paste(x,y, sep="") 

my_query <- gsub(",", " ", N)

#print(my_query)
# run get_pubmed_ids with query as argument
# list with IDs
my_entrez_id <- get_pubmed_ids(my_query) 

# using fetch_pubmed_data() to retrieve pubmed  data from previous search
my_abstracts_xml <- fetch_pubmed_data(pubmed_id_list = my_entrez_id)
fetched_data <- fetch_pubmed_data(my_entrez_id, encoding = "ASCII")

# grep article titles
my_titles <- custom_grep(my_abstracts_xml, "ArticleTitle", "char")
# make dataframe
PubMed_df <- table_articles_byAuth(pubmed_data = fetched_data,
                                   included_authors = "first", encoding = "ASCII")
# save for later
# write.csv(PubMed_df,"PubMed_Articles.csv", row.names = T)

# View(PubMed_df)
PubMed_df$address <- substr(PubMed_df$address, 1, 28)
PubMed_df$jabbrv <- substr(PubMed_df$jabbrv, 1, 9)
sid <- seq(5, nrow(PubMed_df), by = 10)
PubMed_df[sid, c("pmid", "year", "jabbrv", "lastname", "address")] 

# name months
PubMed_df$month[PubMed_df$month=="01"] <- "Jan"
PubMed_df$month[PubMed_df$month=="02"] <- "Feb"
PubMed_df$month[PubMed_df$month=="03"] <- "March"
PubMed_df$month[PubMed_df$month=="04"] <- "Apr"
PubMed_df$month[PubMed_df$month=="05"] <- "May"
PubMed_df$month[PubMed_df$month=="06"] <- "June"
PubMed_df$month[PubMed_df$month=="07"] <- "July"
PubMed_df$month[PubMed_df$month=="08"] <- "Aug"
PubMed_df$month[PubMed_df$month=="09"] <- "Sept"
PubMed_df$month[PubMed_df$month=="10"] <- "Oct"
PubMed_df$month[PubMed_df$month=="11"] <- "Nov"
PubMed_df$month[PubMed_df$month=="12"] <- "Dec"


PubMed_df$month <- factor(PubMed_df$month,
                          levels = c("Jan", "Feb", "March", "Apr", "May", "June", "July","Aug","Sept","Oct", "Nov", "Dec"))

final_path <- paste0(args[2], "/Outputs/temp.png")

png(final_path, width = 500, height = 500)
# Display publications per month:
ggplot(PubMed_df, aes(x = month, fill = year)) + 
        geom_bar() + theme_minimal() +
        labs(x = "Month", y = "Number of papers",
             fill="Year") +
 theme(title = element_text(face = "bold"), axis.text.x = element_text(angle = 45))

dev.off()








