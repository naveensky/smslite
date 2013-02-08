import android.graphics.Path;
import com.greenapplesolutions.le.domain.Citation;
import com.greenapplesolutions.le.domain.Fields;
import com.greenapplesolutions.le.domain.Judgement;
import com.greenapplesolutions.le.search.config.LuceneConfig;
import com.greenapplesolutions.le.util.Util;

import java.io.*;
//import java.nio.file.FileSystems;
//import java.nio.file.Files;
import java.sql.*;
import java.util.*;
import java.util.Date;

public class Loader1 {
    private String _indexPath;
    private String _filesPath;
    private CaseIndexer indexer;
    private LuceneConfig config;
    private int indexJudgmentPerCycle = 1000;
    private int loadCasesPerCycle = 10000;
    private int assumedCases = 60000;
    private int startingCase = 0;


    public Loader1() {
        _indexPath = "D:/le android idx/sikkim";
        config = LuceneConfig.INSTANCE();
        config.setIndexPath(_indexPath);
        indexer = new CaseIndexer(config);
    }

    public static void main(String[] args) {
        new Loader1().loadDatabase();
    }

    private void loadDatabase() {
        try {

//            String[] dbs = new String[]{"le_ap", "le_Bom", "le_calc", "le_chh", "le_Delhi",
//                    "le_ghc", "le_guj", "le_hp", "le_jh", "le_jk", "le_kar", "le_krl", "le_mad",
//                    "le_MP", "le_ori", "le_pat", "le_punjab", "le_raj", "le_sikk", "le_utt", "le_pc"};

            String[] dbs = new String[]{"le_sikkim"};

            for (String courtName : dbs) {
//                String courtName = "le_sikkim";
                Class.forName("com.mysql.jdbc.Driver");
                loadJudgments(courtName);
                loadCitations(courtName);
                loadParties(courtName);
                loadCourt(courtName);
                loadJournals(courtName);
                loadActs(courtName);
                loadJudges(courtName);
                loadVolumes(courtName);
                loadYears(courtName);
                System.out.println("Index created for " + courtName);
            }

            System.out.println("Index creating completed.");

        } catch (Exception x) {
            System.out.println("Error loading database class " + x.getMessage());
        }
    }


    private void loadCitations(String dbName) {
        try {

            Connection conn = getConnection(dbName);
            Statement stmt = conn.createStatement();
            ResultSet results = stmt.executeQuery("SELECT * FROM equivicit");
            List<Citation> citationsList = new ArrayList<Citation>();

            Date defaultDate = new Date(1900, 1, 1);
            int counter = 0;

            while (results.next()) {
                try {
                    Citation citation = new Citation();
                    citation.Page = results.getObject(Fields.Page) == null ? -1 : results.getInt(Fields.Page);
                    citation.Year = results.getObject(Fields.Year) == null ? -1 : results.getInt(Fields.Year);
                    citation.Journal = results.getObject(Fields.Journal) == null ? "" : results.getString(Fields.Journal);

                    if (results.getString(Fields.Volume) == null) {
                        citation.Volume = "";
                    } else {
                        if (results.getString(Fields.Volume).equals("."))
                            citation.Volume = Citation.BlankVolumeReplacement;
                        else
                            citation.Volume = results.getString(Fields.Volume);
                    }

                    citation.Court = results.getObject(Fields.Court) == null ? "" : results.getString(Fields.Court);
                    citation.Appellant = results.getObject(Fields.Appellant) == null ? "" : results.getString(Fields.Appellant);
                    citation.Respondent = results.getObject(Fields.Respondent) == null ? "" : results.getString(Fields.Respondent);
                    citation.CaseDate = results.getObject(Fields.CaseDate) == null ? defaultDate : results.getDate(Fields.CaseDate);
                    citation.Keycode = results.getObject(Fields.Keycode) == null ? -1 : results.getInt(Fields.Keycode);
                    citationsList.add(citation);

                    if (counter > 1000) {
                        indexer.indexCitations(citationsList);
                        citationsList.clear();
                        citationsList = new ArrayList<Citation>();
                        counter = 0;

                        System.out.println("Indexed 1000 citations ...");

                    }
                } catch (Exception ex) {
                    //todo: log
                    //consume the exception to continue the process
                    continue;
                }
            }

            if (citationsList.size() > 0)
                indexer.indexCitations(citationsList);

            System.out.println("Citation Indexing Complete!");


        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    /*
    The format of file would be

    First line will be appellant : "name1","name2","name3"
    Second line will be respondent : "name2","name3"

     */
    private void loadParties(String dbName) {
        try {
            Connection conn = getConnection(dbName);
            Statement stmt = conn.createStatement();
            ResultSet results = stmt.executeQuery("SELECT Respondent, Appellant FROM  citation");


            HashSet<String> appellants = new HashSet<String>();
            HashSet<String> respondents = new HashSet<String>();

            File appellantPath =new File(config.getAppellantCachePath());
            File respondentPath = new File(config.getRespondentCachePath());

            if (appellantPath.exists()) {
                ObjectInputStream inputStream = new ObjectInputStream(new FileInputStream(appellantPath.toString()));
                appellants = (HashSet<String>) inputStream.readObject();
            }

            if (respondentPath.exists()) {
                ObjectInputStream inputStream = new ObjectInputStream(new FileInputStream(respondentPath.toString()));
                respondents = (HashSet<String>) inputStream.readObject();
            }

            if (appellants == null)
                appellants = new HashSet<String>();

            if (respondents == null)
                respondents = new HashSet<String>();

            String data = null;

            while (results.next()) {
                try {
                    data = results.getObject("Appellant") == null ? "" : results.getString("Appellant");
                    appellants.add(data);

                    data = results.getObject("Respondent") == null ? "" : results.getString("Respondent");
                    respondents.add(data);

                } catch (Exception ex) {
                    //todo: log
                    //consume the exception to continue the process
                    continue;
                }
            }


            Set<String> orderSet = new TreeSet<String>(appellants);
            ObjectOutputStream appellantStream = new ObjectOutputStream(new FileOutputStream(appellantPath.toString()));

            appellantStream.writeObject(orderSet);
            appellantStream.flush();
            appellantStream.close();

            ObjectOutputStream respondentStream = new ObjectOutputStream(new FileOutputStream(respondentPath.toString()));

            orderSet = new TreeSet<String>(respondents);

            respondentStream.writeObject(respondents);
            respondentStream.flush();
            respondentStream.close();

        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    private void loadJudgments(String dbName) {
        Connection conn = null;

        try {
            conn = getConnection(dbName);
            int totalCycles = assumedCases / loadCasesPerCycle;
            boolean isEmpty = false;

            for (int i = startingCase; i < totalCycles; ++i) {

                Statement stmt = conn.createStatement(ResultSet.TYPE_SCROLL_SENSITIVE, ResultSet.CONCUR_READ_ONLY);
                String query = String.format("SELECT * FROM CITATION LIMIT %s, %s", (i * loadCasesPerCycle) + startingCase, loadCasesPerCycle - 1);
                ResultSet results = stmt.executeQuery(query);
                List<Judgement> judgments = new ArrayList<Judgement>();
                Date defaultDate = new Date();
                defaultDate.setTime(100000);

                while (results.next()) {
                    try {
                        Judgement j = new Judgement();

                        j.Court = results.getObject("Court") == null ? "" : results.getString("Court");
                        j.Respondant = results.getObject("Respondent") == null ? "" : results.getString("Respondent");
                        j.Appellant = results.getObject("Appellant") == null ? "" : results.getString("Appellant");
                        j.Headnote = results.getObject("Headnote") == null ? "" : results.getString("Headnote");
                        j.FullText = results.getObject("Judgement") == null ? "" : results.getString("Judgement");
                        j.Judges = results.getObject("Judges") == null ? "" : results.getString("Judges");
                        j.CaseNumber = results.getObject(Fields.CaseNumber) == null ? "" : results.getString(Fields.CaseNumber);
                        j.Advocates = results.getObject("Advocates") == null ? "" : results.getString("Advocates");
                        j.CaseDate = results.getObject("Date") == null ? defaultDate : results.getDate("Date");
                        j.Subject = "";
                        j.Keycode = results.getObject("Keycode") == null ? -1 : results.getInt("Keycode");

                        judgments.add(j);

                        if (judgments.size() >= indexJudgmentPerCycle) {
                            indexer.indexJudgements(judgments);

                            //creating new list to help gc
                            judgments = null;
                            judgments = new ArrayList<Judgement>();
                            System.out.println("Indexed " + indexJudgmentPerCycle + " results at " + new Date().toString() + " ...");
                        }
                    } catch (Exception ex) {
                        //consume exception so that process can be continues
                        ex.printStackTrace();
                        continue;
                    }
                }

                if (judgments.size() > 0)
                    indexer.indexJudgements(judgments);

                System.out.println("Indexed all judgments for this cycle...");
                results = null;         //to make gc work easy
            }

            System.out.println("Indexed all judgments, start caching files...");

        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private void loadCourt(String dbName) {
        try {
            Connection conn = getConnection(dbName);
            Statement stmt = conn.createStatement();
            ResultSet results = stmt.executeQuery("SELECT DISTINCT Court FROM  citation");

            HashSet<String> courts = new HashSet<String>();
            File courtCachePath = new File(config.getCourtCachePath());

            if (courtCachePath.exists()) {
                ObjectInputStream inputStream = new ObjectInputStream(new FileInputStream(courtCachePath.toString()));
                courts = (HashSet<String>) inputStream.readObject();
            }
            if (courts == null)
                courts = new HashSet<String>();

            String data = null;

            while (results.next()) {
                data = results.getObject(Fields.Court) == null ? "" : results.getString(Fields.Court);
                if (!Util.isStringNullOrEmpty(data))
                    courts.add(data);
            }

            ObjectOutputStream courtsStream = new ObjectOutputStream(new FileOutputStream(courtCachePath.toString()));

            courtsStream.writeObject(courts);
            courtsStream.flush();
            courtsStream.close();

        } catch (Exception ex) {
            ex.printStackTrace();
        }

    }

    private void loadActs(String dbName) {
        try {
            Connection conn = getConnection(dbName);
            Statement stmt = conn.createStatement();
            ResultSet results = stmt.executeQuery("SELECT DISTINCT actreferred FROM  actsreferred");
            HashSet<String> acts = null;
            String data = null;
            File actsCachePath = new File(config.getActsCachePath());

            if (actsCachePath.exists()) {
                ObjectInputStream inputStream = new ObjectInputStream(new FileInputStream(actsCachePath.toString()));
                acts = (HashSet<String>) inputStream.readObject();
            }
            if (acts == null)
                acts = new HashSet<String>();

            while (results.next()) {
                data = results.getObject(Fields.ActName) == null ? "" : results.getString(Fields.ActName);
                if (!Util.isStringNullOrEmpty(data))
                    acts.add(data);
            }

            ObjectOutputStream actsStream = new ObjectOutputStream(new FileOutputStream(actsCachePath.toString()));

            actsStream.writeObject(acts);
            actsStream.flush();
            actsStream.close();

        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }


    private void loadJournals(String dbName) {
        try {
            Connection conn = getConnection(dbName);
            Statement stmt = conn.createStatement();
            ResultSet results = stmt.executeQuery("SELECT DISTINCT Journal FROM  equivicit");
            HashSet<String> journals = new HashSet<String>();
            String data = null;

            File journalCachePath = new File(config.getJournalsCachePath());

            if (journalCachePath.exists()) {
                ObjectInputStream inputStream = new ObjectInputStream(new FileInputStream(journalCachePath.toString()));
                journals = (HashSet<String>) inputStream.readObject();
            }
            if (journals == null)
                journals = new HashSet<String>();

            while (results.next()) {
                data = results.getObject(Fields.Journal) == null ? "" : results.getString(Fields.Journal);
                if (!Util.isStringNullOrEmpty(data))
                    journals.add(data.toUpperCase());
            }

            ObjectOutputStream actsStream = new ObjectOutputStream(new FileOutputStream(journalCachePath.toString()));
            actsStream.writeObject(journals);
            actsStream.flush();
            actsStream.close();

        } catch (Exception ex) {
            ex.printStackTrace();
        }

    }

    private void loadJudges(String dbName) {
        try {
            Connection conn = getConnection(dbName);
            Statement stmt = conn.createStatement();
            ResultSet results = stmt.executeQuery("SELECT DISTINCT Judges FROM  citation");
            HashSet<String> judges = new HashSet<String>();
            String data = null;

            File judgeCachePath = new File(config.getJudgesCachePath());

            if (judgeCachePath.exists()) {
                ObjectInputStream inputStream = new ObjectInputStream(new FileInputStream(judgeCachePath.toString()));
                judges = (HashSet<String>) inputStream.readObject();
            }

            if (judges == null)
                judges = new HashSet<String>();

            while (results.next()) {
                data = results.getObject(Fields.Judges) == null ? "" : results.getString(Fields.Judges);
                if (!Util.isStringNullOrEmpty(data))
                    judges.add(data.toUpperCase());
            }

            ObjectOutputStream actsStream = new ObjectOutputStream(new FileOutputStream(judgeCachePath.toString()));
            actsStream.writeObject(judges);
            actsStream.flush();
            actsStream.close();

        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    private void loadYears(String dbName) {
        try {
            Connection conn = getConnection(dbName);
            Statement stmt = conn.createStatement();
            ResultSet results = stmt.executeQuery("SELECT distinct year FROM equivicit");
            HashSet<String> years = new HashSet<String>();
            String data = null;

            File yearCachePath = new File(config.getYearCachePath());

            if (yearCachePath.exists()) {
                ObjectInputStream inputStream = new ObjectInputStream(new FileInputStream(yearCachePath.toString()));
                years = (HashSet<String>) inputStream.readObject();
            }

            if (years == null)
                years = new HashSet<String>();

            while (results.next()) {
                data = results.getObject(Fields.Year) == null ? "" : results.getString(Fields.Year);
                if (!Util.isStringNullOrEmpty(data))
                    years.add(data.toUpperCase());
            }

            ObjectOutputStream actsStream = new ObjectOutputStream(new FileOutputStream(yearCachePath.toString()));
            actsStream.writeObject(years);
            actsStream.flush();
            actsStream.close();

        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    private void loadVolumes(String dbName) {
        try {
            Connection conn = getConnection(dbName);
            Statement stmt = conn.createStatement();
            ResultSet results = stmt.executeQuery("SELECT distinct volume FROM equivicit");
            HashSet<String> volume = new HashSet<String>();
            String data = null;

            File volumeCachePath = new File(config.getVolumeCachePath());

            if (volumeCachePath.exists()) {
                ObjectInputStream inputStream = new ObjectInputStream(new FileInputStream(volumeCachePath.toString()));
                volume = (HashSet<String>) inputStream.readObject();
            }

            if (volume == null)
                volume = new HashSet<String>();

            while (results.next()) {
                data = results.getObject(Fields.Volume) == null ? "" : results.getString(Fields.Volume);
                if (!Util.isStringNullOrEmpty(data))
                    volume.add(data.toUpperCase());
            }

            ObjectOutputStream actsStream = new ObjectOutputStream(new FileOutputStream(volumeCachePath.toString()));
            actsStream.writeObject(volume);
            actsStream.flush();
            actsStream.close();

        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

    private Connection getConnection(String dbName) throws SQLException {
        Connection conn = null;
        String dbUrl = "jdbc:mysql://localhost/" + dbName;
        conn = DriverManager.getConnection(dbUrl, "root", "");
        System.out.println("Connected to database " + dbName);
        return conn;
    }
}