import javax.swing.*;
import javax.swing.table.DefaultTableCellRenderer;
import javax.swing.table.TableCellEditor;
import javax.swing.text.html.HTMLDocument;
import java.awt.*;
import java.awt.event.ActionEvent;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.MalformedURLException;
import java.net.URL;
import java.util.*;
import java.util.List;

public class Main {
    private static JFrame jFrame = getJFrame();
    private static JPanel startPanel = new JPanel();
    private static JButton sendDifficultButton = new JButton("Send Difficult");
    private static int difficult;
    private static URL url;
    private static int[][] sudokuMap = new int[9][9];
    private static int[][] sudokuSol = new int[9][9];
    private static Object[][] gameMap = new Object[9][9];
    private static JTextField textFieldDif = new JTextField();
    private static JButton checkButton = new JButton();
    private static JTable jGameTable = new JTable();

    public static int[][] getSudokuMap() {
        return sudokuMap;
    }

    public static void main(String[] args){
        setStartConfig();

    }

    private static void setStartConfig(){
        textFieldDif = new JTextField(5);
        sendDifficultButton.addActionListener(e ->{
            int x = 0;
            try {
                x = Integer.parseInt(textFieldDif.getText());
                difficult = x;
                if(difficult > 80 || difficult  < 48){
                    throw new NumberFormatException();
                }
                var path = String.format("http://www.test.site/PlaySudoku.php?difficult=%s", difficult);
                url = new URL(path);
                getContent();
                createGame();
            }
            catch (NumberFormatException e1)
            {
                JOptionPane.showMessageDialog(startPanel, "Некорректный ввод!");
            }
            catch (MalformedURLException e2){
                JOptionPane.showMessageDialog(startPanel, "Ошибка соединения с сервером");
            } catch (IOException | InterruptedException ioException) {
                JOptionPane.showMessageDialog(startPanel, "Ошибка получения данных");
            }
        });


        startPanel.add(sendDifficultButton);
        startPanel.add(textFieldDif);
        jFrame.add(startPanel);
        jFrame.validate();
    }

    private static void createGame() {
        var a = new String[]{
                " ", " ", " ", " ", " ", " ", " ", " ", " "
        };

        fillMapArray();
        jGameTable = new JTable(gameMap, a){
            @Override
            public int getRowHeight() {
                return this.getColumnModel().getColumn(0).getWidth();
            }

            @Override
            public boolean isCellEditable(int row, int column) {
                if(sudokuMap[row][column] == 0) return true;
                return false;
            }
        };
        checkButton = new JButton("Ok");
        checkButton.addActionListener(e-> {
            if(checkWin()){
                JOptionPane.showMessageDialog(startPanel, "Вы победили!)");
                jGameTable.removeAll();
                startPanel.removeAll();
                startPanel = new JPanel();
                jFrame.validate();
                setStartConfig();
            }
            else {
                JOptionPane.showMessageDialog(startPanel, "Вы проиграли!(");
                jGameTable.removeAll();
                startPanel.removeAll();
                startPanel = new JPanel();
                jFrame.validate();
                setStartConfig();
            }

        });
        startPanel.add(checkButton);
        startPanel.add(jGameTable);
        var centerRenderer = new PaintTableCellRenderer();
        centerRenderer.setHorizontalAlignment( JLabel.CENTER );
        for(int x=0;x<9;x++){
            jGameTable.getColumnModel().getColumn(x).setCellRenderer( centerRenderer );
        }
        jFrame.validate();

    }

    private static boolean checkWin() {
        for (int i = 0; i < 9; i++) {
            for (int j = 0; j < 9; j++) {
                if(Integer.parseInt(gameMap[i][j].toString()) != sudokuSol[i][j]){
                    return false;
                }
            }
        }
        return true;
    }

    private static void fillMapArray(){
        for (int i = 0; i < 9; i++) {
            for (int j = 0; j < 9; j++) {
                gameMap[i][j] = sudokuMap[i][j];
            }
        }
    }

    private static void getContent() throws IOException, InterruptedException {
        var con = url.openConnection();
        var content = new LinkedList<String>();
        BufferedReader reader = new BufferedReader(
                new InputStreamReader(con.getInputStream(), "UTF-8"));
        while (true) {
            String line = reader.readLine();
            if (line == null)
                break;
            content.add(line);
        }
        reader.close();
        parseContent(content);
    }

    private static void parseContent(Queue<String> content) {
        for (int i = 0; i < 9; i++) {
            var buf = content.poll().trim().split(" ");
            for (int j = 0; j < 9; j++) {
                sudokuMap[i][j] = Integer.parseInt(buf[j]);
            }
        }
        for (int i = 0; i < 9; i++) {
            var buf = content.poll().trim().split(" ");
            for (int j = 0; j < 9; j++) {
                sudokuSol[i][j] = Integer.parseInt(buf[j]);
            }
        }
        int i = 9;
    }


    private static JFrame getJFrame(){
        JFrame jFrame = new JFrame(){};
        jFrame.setVisible(true);
        jFrame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        jFrame.setSize(800,800);
        jFrame.setLocation(560,240);
        jFrame.setTitle("Sudoku");
        return jFrame;
    }

}
class PaintTableCellRenderer extends DefaultTableCellRenderer{
    @Override
    public Component getTableCellRendererComponent(JTable table, Object value, boolean isSelected, boolean hasFocus, int row, int column) {
        super.getTableCellRendererComponent(table, value, isSelected, hasFocus, row, column);
        if (Main.getSudokuMap()[row][column] == 0) {
            if(Integer.parseInt(value.toString()) > 9 || Integer.parseInt(value.toString()) < 0){
                setValue(0);
            }
            Color color = Color.ORANGE;
            setBackground(color);
        }
        else {

            setBackground(Color.WHITE);
        }
        return this;
    }
}
