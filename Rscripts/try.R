
args <- commandArgs(TRUE)
N <- args[1]
N <- gsub(",", " ", N)
M <- args[2]
print (args)

filename <- paste0("articles_year",sample.int(1000000,1),".png")
final_path <- paste0(args[2], "/Outputs/", filename)


paste0()
sink("outfile.txt")
cat(N)
cat(final_path)
sink()

